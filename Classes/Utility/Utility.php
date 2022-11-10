<?php

namespace Magrunert\DocuCe\Utility;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\StartTimeRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\EndTimeRestriction;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Utility class
 */
class Utility
{

    /**
     * @var ConnectionPool
     */
    protected ConnectionPool $connectionPool;

    /**
     * @param ConnectionPool $connectionPool
     */
    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    /**
     * Get all ctypes
     *
     * @return array
     */
    public function getAllCtypes(): array
    {
        /**
         *
         * Get all ctypes (including hidden content elements) that have been inserted on the website. Deleted elements are not displayed
         *
         * @return array
         *
         */

        $qb = $this->connectionPool->getQueryBuilderForTable('tt_content');
        $qb
            ->getRestrictions()
            ->removeByType(HiddenRestriction::class)
            ->removeByType(StartTimeRestriction::class)
            ->removeByType(EndTimeRestriction::class);

        $qb->select('c.CType');
        $qb->addSelectLiteral('COUNT(*) AS ' . $qb->quoteIdentifier('count'));
        $qb->from('tt_content', 'c');
        $qb->leftJoin('c', 'pages', 'p', 'c.pid = p.uid');
        $qb->groupBy('c.CType');
        $qb->orderBy('c.CType');

        $result = $qb->execute()->fetchAll();

        return $result;
    }

    public function getCtype($ctype): array
    {
        /**
         *
         * Get fields from tt_content by ctype (including hidden content elements).
         *
         * @return array
         *
         */

        $qb = $this->connectionPool->getQueryBuilderForTable('tt_content');
        $qb
            ->getRestrictions()
            ->removeByType(HiddenRestriction::class);

        $qb->select('c.header as content_header','c.uid as content_uid','p.uid as page_uid','c.hidden as content_hidden','c.colPos as content_colPos','p.title as page_title','p.doktype as page_doktype','p.sys_language_uid as page_language','p.hidden as page_hidden');
        $qb->from('tt_content', 'c');
        $qb->leftJoin('c', 'pages', 'p', 'c.pid = p.uid');
        $qb->where(
            $qb->expr()->eq('CType', '"'. $ctype . '"')
        );
        $qb->orderBy('page_uid');

        $result = $qb->execute()->fetchAll();

        return $result;
    }

    public function getWizardItems($cType): array {
        /**
         *
         * Page Config: get Content Element Information from mod.wizards Array for a special cType: ceWizardItems
         *
         * @return      array
         *
         */

        //@TBD PAGE ROOT ID, if more page tree exist
        $ceWizardItems = BackendUtility::getPagesTSconfig(0)['mod.']['wizards.']['newContentElement.']['wizardItems.'] ?? [];

        $ceWizardItem = [];
        if(is_array($ceWizardItems) && count($ceWizardItems) > 0) {
            foreach($ceWizardItems as $item) {

                // @TBD regex contains string einbauen
                if (array_search($cType . '.', array_keys($item['elements.']))) {
                    $ceWizardItem = $item['elements.'][$cType. '.'];
                    break;
                }
            }
        }

        return $ceWizardItem;
    }

    public function getBackendlayouts(): array
    {
        /**
         *
         * Page Config: get configured Backend Layouts from pageTSconfig mod.web_layout
         *
         * @return      array
         *
         */

        $backendLayouts = BackendUtility::getPagesTSconfig(0)['mod.']['web_layout.']['BackendLayouts.'] ?? [];

        $backendLayout = [];
        if(is_array($backendLayouts) && count($backendLayouts) > 0) {
            foreach ($backendLayouts as $key => $item) {
                $backendLayout[] = [
                    'key' => $key,
                    'title' => $item['title'],
                    'icon' => $item['icon'],
                ];
            }
        }

        return $backendLayout;
    }

}