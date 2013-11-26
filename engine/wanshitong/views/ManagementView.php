<?php namespace wanshitong\views;

use wanshitong\TemplateManager;

/**
 * Book management interface.
 *
 * @author kmacphail
 * @version 1.0.0
 * @since 1.0.0
 */
class ManagementView extends PageView
{
    /**
     * Construct the view.
     *
     * @param stdClass[] $authors
     * @param stdClass[] $sections
     */
    public function __construct($authors, $sections)
    {
        $content = TemplateManager::getDefaultTemplateManager()->load('management', array('authors' => $authors, 'sections' => $sections));
        parent::__construct('Inventory Management', $content);
    }
}