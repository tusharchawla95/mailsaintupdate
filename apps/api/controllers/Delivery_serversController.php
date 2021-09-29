<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * Delivery_serversController
 *
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.17
 */

class Delivery_serversController extends Controller
{
	/**
	 * @var array
	 */
    public $cacheableActions = array('index', 'view');

    // access rules for this controller
    public function accessRules()
    {
        return array(
            // allow all authenticated users on all actions
            array('allow', 'users' => array('@')),
            // deny all rule.
            array('deny'),
        );
    }

    /**
     * Handles the listing of the delivery servers.
     * The listing is based on page number and number of servers per page.
     * This action will produce a valid ETAG for caching purposes.
     *
     * @return BaseController
     */
    public function actionIndex()
    {
        $request    = Yii::app()->request;
        $perPage    = (int)$request->getQuery('per_page', 10);
        $page       = (int)$request->getQuery('page', 1);
        $maxPerPage = 50;
        $minPerPage = 10;

        if ($perPage < $minPerPage) {
            $perPage = $minPerPage;
        }

        if ($perPage > $maxPerPage) {
            $perPage = $maxPerPage;
        }

        if ($page < 1) {
            $page = 1;
        }

        $data = array(
            'count'         => null,
            'total_pages'   => null,
            'current_page'  => null,
            'next_page'     => null,
            'prev_page'     => null,
            'records'       => array(),
        );

        $criteria = new CDbCriteria();
        $criteria->select = 't.server_id, t.type, t.name, t.hostname, t.status';
        $criteria->compare('t.customer_id', (int)Yii::app()->user->getId());
        $count = DeliveryServer::model()->count($criteria);

        if ($count == 0) {
            return $this->renderJson(array(
                'status'    => 'success',
                'data'      => $data
            ), 200);
        }

        $totalPages = ceil($count / $perPage);

        $data['count']          = $count;
        $data['current_page']   = $page;
        $data['next_page']      = $page < $totalPages ? $page + 1 : null;
        $data['prev_page']      = $page > 1 ? $page - 1 : null;
        $data['total_pages']    = $totalPages;

        $criteria->order = 't.name ASC';
        $criteria->limit = $perPage;
        $criteria->offset= ($page - 1) * $perPage;

        $servers = DeliveryServer::model()->findAll($criteria);

        foreach ($servers as $server) {
            $record = $server->getAttributes(array('server_id', 'type', 'name', 'hostname', 'status'));
            $data['records'][] = $record;
        }

        return $this->renderJson(array(
            'status'    => 'success',
            'data'      => $data
        ), 200);
    }

    /**
     * Handles the listing of a single delivery server.
     * This action will produce a valid ETAG for caching purposes.
     *
     * @param $server_id
     * @return BaseController
     */
    public function actionView($server_id)
    {
        if (!($server = $this->loadServerById((int)$server_id))) {
            return $this->renderJson(array(
                'status'    => 'error',
                'error'     => Yii::t('api', 'The server does not exist.')
            ), 404);
        }

        $recordData = $server->getAttributes(array('server_id', 'type', 'name', 'hostname', 'status'));

        $data = array('record' => $recordData);

        return $this->renderJson(array(
            'status'    => 'success',
            'data'      => $data,
        ), 200);
    }

    /**
     * It will generate the timestamp that will be used to generate the ETAG for GET requests.
     *
     * @return float|int
     */
    public function generateLastModified()
    {
        static $lastModified;

        if ($lastModified !== null) {
            return $lastModified;
        }

        $request    = Yii::app()->request;
        $row        = array();

        if ($this->action->id == 'index') {

           $perPage    = (int)$request->getQuery('per_page', 10);
            $page       = (int)$request->getQuery('page', 1);

            $maxPerPage = 50;
            $minPerPage = 10;

            if ($perPage < $minPerPage) {
                $perPage = $minPerPage;
            }

            if ($perPage > $maxPerPage) {
                $perPage = $maxPerPage;
            }

            if ($page < 1) {
                $page = 1;
            }

            $limit  = $perPage;
            $offset = ($page - 1) * $perPage;
        }

        if ($this->action->id == 'index') {

            $sql = '
                SELECT AVG(t.last_updated) as `timestamp`
                FROM (
                SELECT `a`.`customer_id`, UNIX_TIMESTAMP(`a`.`last_updated`) as `last_updated`
                FROM `{{delivery_server}}` `a` 
                WHERE `a`.`customer_id` = :cid 
                ORDER BY `a`.`name` ASC 
                LIMIT :l OFFSET :o
                ) AS t 
                WHERE `t`.`customer_id` = :cid
            ';

            $command = Yii::app()->getDb()->createCommand($sql);
            $command->bindValue(':cid', (int)Yii::app()->user->getId(), PDO::PARAM_INT);
            $command->bindValue(':l', (int)$limit, PDO::PARAM_INT);
            $command->bindValue(':o', (int)$offset, PDO::PARAM_INT);

            $row = $command->queryRow();

        } elseif ($this->action->id == 'view') {

            $sql = 'SELECT UNIX_TIMESTAMP(t.last_updated) as `timestamp` FROM `{{delivery_server}}` t WHERE `t`.`server_id` = :id AND `t`.`customer_id` = :cid LIMIT 1';
            $command = Yii::app()->getDb()->createCommand($sql);
            $command->bindValue(':id', $request->getQuery('server_id'), PDO::PARAM_STR);
            $command->bindValue(':cid', (int)Yii::app()->user->getId(), PDO::PARAM_INT);

            $row = $command->queryRow();
        }

        if (isset($row['timestamp'])) {
            $timestamp = round($row['timestamp']);
            if (preg_match('/\.(\d+)/', $row['timestamp'], $matches)) {
                $timestamp += (int)$matches[1];
            }
            return $lastModified = $timestamp;
        }

        return $lastModified = parent::generateLastModified();
    }

    /**
     * @param int $server_id
     * @return null|DeliveryServer
     */
    public function loadServerById($server_id)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('server_id', $server_id);
        $criteria->compare('customer_id', (int)Yii::app()->user->getId());
        return DeliveryServer::model()->find($criteria);
    }
}
