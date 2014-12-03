<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Ushahidi Layer Repository
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Application
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

use Ushahidi\Core\Entity;
use Ushahidi\Core\Entity\Layer;
use Ushahidi\Core\SearchData;

class Ushahidi_Repository_Layer extends Ushahidi_Repository
{
	// Ushahidi_Repository
	protected function getTable()
	{
		return 'layers';
	}

	// Ushahidi_Repository
	public function getEntity(Array $data = null)
	{
		return new Layer($data);
	}

	// SearchRepository
	public function getSearchFields()
	{
		return ['active', 'type'];
	}

	// Ushahidi_Repository
	protected function setSearchConditions(SearchData $search)
	{
		$query = $this->search_query;

		if ($search->active !== null) {
			$query->where('active', '=', $search->active);
		}

		if ($search->type) {
			$query->where('type', '=', $search->type);
		}
	}

	// CreateRepository
	public function create(Entity $entity)
	{
		$record = $entity->asArray();

		$record['created'] = time();
		$record['options'] = json_encode($record['options']);

		return $this->executeInsert($this->removeNullValues($record));
	}

	// UpdateRepository
	public function update(Entity $entity)
	{
		$update = $entity->getChanged();

		$update['updated'] = time();
		if (isset($update['options']))
		{
			$update['options'] = json_encode($update['options']);
		}

		return $this->executeUpdate(['id' => $entity->id], $update);
	}
}
