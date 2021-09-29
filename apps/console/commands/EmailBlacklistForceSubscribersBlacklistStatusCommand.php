<?php defined('MW_PATH') or exit('No direct script access allowed');

/**
 * EmailBlacklistForceSubscribersBlacklistStatusCommand
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.8
 */

class EmailBlacklistForceSubscribersBlacklistStatusCommand extends ConsoleCommand
{
    /**
     * @return int
     */
    public function actionIndex()
    {
        $input = $this->confirm('This will change the subscribers status matching the blacklist regardless of their current status. Are you sure?');

        if (!$input) {
            return 0;
        }

        $mutexKey = sha1(__METHOD__);

        if (!Yii::app()->mutex->acquire($mutexKey, 5)) {
            $this->stdout('Unable to acquire the mutex lock!');
            return 1;
        }

        try {

            Yii::app()->hooks->doAction('console_command_email_blacklist_force_subscribers_blacklist_status_before_process', $this);

            $this->process();

            Yii::app()->hooks->doAction('console_command_email_blacklist_force_subscribers_blacklist_status_process', $this);

        } catch (Exception $e) {

            $this->stdout(__LINE__ . ': ' .  $e->getMessage());
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);

            return 1;
        }

        Yii::app()->mutex->release($mutexKey);

        return 0;
    }

    /**
     * @throws CException
     */
    public function process()
    {
        $limit  = 1000;
        $offset = 0;

        $blacklistAddresses = $this->getBlacklistAddresses($limit, $offset);
        if (empty($blacklistAddresses)) {
            $this->stdout('Done, nothing else to process!');
            return;
        }

        $this->stdout(sprintf('Started a batch from %d to %d and found %d results to process...', $offset, $limit, count($blacklistAddresses)));

        while (!empty($blacklistAddresses)) {

            $blacklistAddressesParts = array_chunk($blacklistAddresses, 100);

            foreach ($blacklistAddressesParts as $blacklistAddressesPart) {
                $criteria = new CDbCriteria();
                $criteria->addInCondition('email', $blacklistAddressesPart);
                $criteria->compare('status', ListSubscriber::STATUS_CONFIRMED);

                ListSubscriber::model()->updateAll(array(
                    'status'       => ListSubscriber::STATUS_BLACKLISTED,
                    'last_updated' => new CDbExpression('NOW()')
                ), $criteria);
            }

            $offset = $offset + $limit;
            $blacklistAddresses = $this->getBlacklistAddresses($limit, $offset);

            if (!empty($blacklistAddresses)) {
                $this->stdout(sprintf('Started a batch from %d to %d and found %d results to process...', $offset, $offset + $limit, count($blacklistAddresses)));
            } else {
                $this->stdout('Done, nothing else to process!');
            }
        }
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    protected function getBlacklistAddresses($limit, $offset)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'email';
        $criteria->limit  = $limit;
        $criteria->offset = $offset;

        /** @var EmailBlacklist[] $models */
        $models = EmailBlacklist::model()->findAll($criteria);
        $addresses = array();

        /** @var EmailBlacklist $model */
        foreach ($models as $model) {
            $addresses[] = $model->email;
        }

        return $addresses;
    }
}
