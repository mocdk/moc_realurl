<?php
class ux_tx_realurl_tcemain extends tx_realurl_tcemain {

	/**
	 * Clears RealURL caches if necessary
	 *
	 * @param string $command
	 * @param string $tableName
	 * @param int $recordId
	 * @return void
	 */
	protected function clearCaches($command, $tableName, $recordId) {
		if ($this->isTableForCache($tableName)) {
			if ($command == 'delete' || $command == 'move') {
				list($pageId, ) = $this->getPageData($tableName, $recordId);
				$this->fetchRealURLConfiguration($pageId);
				$pageIds = $this->getSubpageIdsFromPageId($pageId);
				if ($command == 'delete') {
					$this->clearPathCache($pageIds);
				}
				else {
					$this->expirePathCacheForAllLanguages($pageIds);
				}
				$this->clearOtherCaches($pageIds);
			}
		}
	}

	/**
	 * Processes page and content changes in regard to RealURL caches.
	 *
	 * @param string $status
	 * @param string $tableName
	 * @param int $recordId
	 * @param array $databaseData
	 * @return void
	 * @todo Handle changes to tx_realurl_exclude recursively
	 */
	protected function processContentUpdates($status, $tableName, $recordId, array $databaseData) {
		if ($status == 'update' && tx_realurl::testInt($recordId)) {
			list($pageId, $languageId) = $this->getPageData($tableName, $recordId);
			$this->fetchRealURLConfiguration($pageId);
			if ($this->shouldFixCaches($tableName, $databaseData)) {
				$pageIds = $this->getSubpageIdsFromPageId($pageId);
				if (isset($databaseData['alias'])) {
					$this->expirePathCacheForAllLanguages($pageIds);
				}
				else {
					$this->expirePathCache($pageIds, $languageId);
				}
				$this->clearOtherCaches($pageIds);
			}
		}
	}

	/**
	 * Clears URL decode and encode caches for the given page
	 *
	 * @param array $pageIds
	 * @return void
	 */
	protected function clearOtherCaches($pageIds) {
		$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_realurl_urldecodecache', sprintf('page_id IN (%s)', implode(',', $pageIds)));
		$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_realurl_urlencodecache', sprintf('page_id IN (%s)', implode(',', $pageIds)));
	}

	/**
	 * Clears path cache for the given page id
	 *
	 * @param array $pageIds
	 * @return void
	 */
	protected function clearPathCache($pageIds) {
		$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_realurl_pathcache', sprintf('page_id IN (%s)', implode(',', $pageIds)));
	}

	/**
	 * Expires record in the path cache
	 *
	 * @param array $pageIds
	 * @return void
	 */
	protected function expirePathCacheForAllLanguages($pageIds) {
		$expirationTime = $this->getExpirationTime();
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_realurl_pathcache', sprintf('page_id IN (%s) AND expire = 0', implode(',', $pageIds), array('expire' => $expirationTime)));
	}

	/**
	 * Expires record in the path cache
	 *
	 * @param array $pageIds
	 * @param int $languageId
	 * @return void
	 */
	protected function expirePathCache($pageIds, $languageId) {
		$expirationTime = $this->getExpirationTime();
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_realurl_pathcache', sprintf('page_id IN (%s) AND language_id = %u AND expire = 0', implode(',', $pageIds), $languageId), array('expire' => $expirationTime));
	}

	/**
	 * @param integer $pageId
	 * @param array $pageIds
	 * @return array
	 */
	protected function getSubpageIdsFromPageId($pageId, array &$pageIds = array()) {
		foreach ($this->getSubpages($pageId) as $subpage) {
			if ($this->getSubpages($subpage['uid'])) {
				$this->getSubpageIdsFromPageId($subpage['uid'], $pageIds);
			}
			array_push($pageIds, $subpage['uid']);
		}
		return $pageIds;
	}

	/**
	 * @param integer $pageId
	 * @return array
	 */
	protected function getSubpages($pageId) {
		return $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid', 'pages', sprintf('pid = %u AND NOT deleted', $pageId));
	}

}