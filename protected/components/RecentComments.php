<?php
class RecentComments extends Portlet
{
    public $title='RecentComments';
    public function getRecentComments()
    {
        return Comment::model()->findRecentComments();
    }
    protected function renderContent()
    {
        $this->render('recentcomments');
    }
}
?>
