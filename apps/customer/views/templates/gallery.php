<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * This file is part of the MailWizz EMA application.
 *
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.3.4.7
 */

/**
 * This hook gives a chance to prepend content or to replace the default view content with a custom content.
 * Please note that from inside the action callback you can access all the controller view
 * variables via {@CAttributeCollection $collection->controller->data}
 * In case the content is replaced, make sure to set {@CAttributeCollection $collection->renderContent} to false
 * in order to stop rendering the default content.
 * @since 1.3.3.1
 */
$hooks->doAction('before_view_file_content', $viewCollection = new CAttributeCollection(array(
    'controller'    => $this,
    'renderContent' => true,
)));

// and render if allowed
if ($viewCollection->renderContent) {
    /**
     * @since 1.3.9.2
     */
    $itemsCount = CustomerEmailTemplate::model()->count('customer_id IS NULL');
    ?>
    <div class="box box-primary borderless">
        <div class="box-header">
            <div class="pull-left">
                <?php BoxHeaderContent::make(BoxHeaderContent::LEFT)
                    ->add('<h3 class="box-title">' . IconHelper::make('glyphicon-text-width') . $pageHeading . '</h3>')
                    ->render();
                ?>
            </div>
            <div class="pull-right">
                <?php BoxHeaderContent::make(BoxHeaderContent::RIGHT)
                    ->addIf($this->widget('common.components.web.widgets.GridViewToggleColumns', array('model' => $template, 'columns' => array('screenshot', 'name', 'category_id', 'date_added', 'last_updated')), true), $itemsCount)
                    ->add(CHtml::link(IconHelper::make('refresh') . Yii::t('app', 'Refresh'), array('templates/gallery'), array('class' => 'btn btn-primary btn-flat', 'title' => Yii::t('app', 'Refresh'))))
                    ->render();
                ?>
            </div>
            <div class="clearfix"><!-- --></div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <?php
                /**
                 * This hook gives a chance to prepend content or to replace the default grid view content with a custom content.
                 * Please note that from inside the action callback you can access all the controller view
                 * variables via {@CAttributeCollection $collection->controller->data}
                 * In case the content is replaced, make sure to set {@CAttributeCollection $collection->renderGrid} to false
                 * in order to stop rendering the default content.
                 * @since 1.3.3.1
                 */
                $hooks->doAction('before_grid_view', $collection = new CAttributeCollection(array(
                    'controller'  => $this,
                    'renderGrid'  => true,
                )));


                /**
                 * This widget renders default getting started page for this particular section.
                 * @since 1.3.9.2
                 */
                $this->widget('common.components.web.widgets.StartPagesWidget', array(
                    'collection' => $collection,
                    'enabled'    => !$itemsCount,
                ));

                // and render if allowed
                if ($collection->renderGrid) {
                    $this->widget('zii.widgets.grid.CGridView', $hooks->applyFilters('grid_view_properties', array(
                        'ajaxUrl'           => $this->createUrl($this->route),
                        'id'                => $template->modelName.'-grid',
                        'dataProvider'      => $template->search(),
                        'filter'            => $template,
                        'filterPosition'    => 'body',
                        'filterCssClass'    => 'grid-filter-cell',
                        'itemsCssClass'     => 'table table-hover',
                        'selectableRows'    => 0,
                        'enableSorting'     => false,
                        'cssFile'           => false,
                        'pagerCssClass'     => 'pagination pull-right',
                        'pager'             => array(
                            'class'         => 'CLinkPager',
                            'cssFile'       => false,
                            'header'        => false,
                            'htmlOptions'   => array('class' => 'pagination')
                        ),
                        'columns' => $hooks->applyFilters('grid_view_columns', array(
                            array(
                                'name'  => 'screenshot',
                                'type'  => 'raw',
                                'value' => 'CHtml::image($data->getScreenshotSrc(), "", array("class" => "img-round", "width" => 120))',
                                'filter'=> false,
                            ),
                            array(
                                'name'  => 'name',
                                'value' => '$data->name',
                            ),
                            array(
                                'name'  => 'category_id',
                                'value' => '!empty($data->category_id) ? $data->category->name : null',
                                'filter'=> CustomerEmailTemplateCategory::getAllAsOptions()
                            ),
                            array(
                                'name'  => 'date_added',
                                'value' => '$data->dateAdded',
                                'filter'=> false,
                            ),
                            array(
                                'name'  => 'last_updated',
                                'value' => '$data->lastUpdated',
                                'filter'=> false,
                            ),
                            array(
                                'class'     => 'CButtonColumn',
                                'header'    => Yii::t('app', 'Options'),
                                'footer'    => $template->paginationOptions->getGridFooterPagination(),
                                'buttons'   => array(
                                    'import' => array(
                                        'label'     => IconHelper::make('import') . ' ' . Yii::t('app', 'Import'),
                                        'url'       => 'Yii::app()->createUrl("templates/gallery_import", array("template_uid" => $data->template_uid))',
                                        'imageUrl'  => null,
                                        'options'   => array('title' => Yii::t('app', 'Import'), 'class' => 'btn btn-primary btn-flat'),
                                    ),
                                ),
                                'headerHtmlOptions' => array('style' => 'text-align: right'),
                                'footerHtmlOptions' => array('align' => 'right'),
                                'htmlOptions'       => array('align' => 'right', 'class' => 'options'),
                                'template'          => '{import}'
                            ),
                        ), $this),
                    ), $this));
                }
                /**
                 * This hook gives a chance to append content after the grid view content.
                 * Please note that from inside the action callback you can access all the controller view
                 * variables via {@CAttributeCollection $collection->controller->data}
                 * @since 1.3.3.1
                 */
                $hooks->doAction('after_grid_view', new CAttributeCollection(array(
                    'controller'  => $this,
                    'renderedGrid'=> $collection->renderGrid,
                )));
                ?>
                <div class="clearfix"><!-- --></div>
            </div>
        </div>
    </div>
    <?php
}
/**
 * This hook gives a chance to append content after the view file default content.
 * Please note that from inside the action callback you can access all the controller view
 * variables via {@CAttributeCollection $collection->controller->data}
 * @since 1.3.3.1
 */
$hooks->doAction('after_view_file_content', new CAttributeCollection(array(
    'controller'        => $this,
    'renderedContent'   => $viewCollection->renderContent,
)));