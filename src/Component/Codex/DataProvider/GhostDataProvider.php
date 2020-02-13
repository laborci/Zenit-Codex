<?php namespace Zenit\Bundle\Codex\Component\Codex\DataProvider;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenit\Bundle\Codex\Interfaces\DataProviderInterface;
use Zenit\Bundle\Codex\Interfaces\ItemDataImporterInterface;
use Zenit\Bundle\Ghost\Entity\Component\Ghost;

class GhostDataProvider implements DataProviderInterface{

	protected $ghost;
	/** @var \Zenit\Bundle\Ghost\Entity\Component\Model model */
	protected $model;

	public function __construct($ghost){
		$this->ghost = $ghost;
		$this->model = $ghost::$model;
	}

	public function getList($page, $sorting, $filter, $pageSize, &$count): array{
		$finder = $this->model->repository->search($this->createFilter($filter))->orderIf(!is_null($sorting), $sorting['field'] . ' ' . $sorting['dir']);
		return $finder->collectPage($pageSize, $page, $count);
	}

	public function convertItem($item): array{
		/** @var Ghost $item */
		return $item->export();
	}

	public function createFilter($filter=null){ return null; }

	public function getItem($id): ?Ghost{ return $this->model->repository->pick($id); }

	public function getNewItem(): Ghost{ return $this->model->createGhost(); }

	public function deleteItem($id){ return $this->model->repository->delete($id); }

	public function updateItem($id, array $data, ItemDataImporterInterface $itemDataImporter){
		/** @var Ghost $item */
		$item = $this->getItem($id);
		$item = $itemDataImporter->importItemData($item, $data);
		return $item->save();
	}

	public function createItem(array $data, ItemDataImporterInterface $itemDataImporter){
		/** @var Ghost $item */
		$item = $this->getNewItem();
		$item = $itemDataImporter->importItemData($item, $data);
		return $item->save();
	}

	public function importItemData($item, $data){
		/** @var Ghost $item */
		$item->import($data);
		return $item;
	}

	public function uploadAttachment($id, $category, UploadedFile $file){
		$item = $this->getItem($id);
		$categoryManager = $item->getAttachmentCategoryManager($category);
		$categoryManager->addFile($file);
	}

	public function getAttachments($id): array{
		$item = $this->getItem($id);
		$categories = $item->getAttachmentCategories();
		$collection = [];
		foreach ($categories as $category){
			$attachments = $item->getAttachmentCategoryManager($category->getName())->all;
			$collection[$category->getName()] = [];
			foreach ($attachments as $attachment){
				$record = $attachment->getRecord();
				if (substr($record['mime-type'],0,6) === 'image/'){
					$record['thumbnail'] = in_array($record['extension'], ['png', 'gif', 'jpg', 'jpeg']) ? $attachment->thumbnail->crop(100, 100)->png : $attachment->url;
				}
				$collection[$category->getName()][] = $record;
			}
		}
		return $collection;
	}

	public function copyAttachment($id, $file, $source, $target){
		$item = $this->getItem($id);
		$item->getAttachmentCategoryManager($target)->addFile($item->getAttachmentCategoryManager($source)->get($file));
	}
	public function moveAttachment($id, $file, $source, $target){
		$item = $this->getItem($id);
		$item->getAttachmentCategoryManager($target)->addFile($item->getAttachmentCategoryManager($source)->get($file));
		$item->getAttachmentCategoryManager($source)->get($file)->remove();
	}
	
	public function deleteAttachment($id, $file, $category){
		$item = $this->getItem($id);
		$item->getAttachmentCategoryManager($category)->get($file)->remove();
	}
}

