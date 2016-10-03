<?php namespace App\Repositories;

use App\Core\EloquentRepository;
use App\Models\Page as PageModel;

/**
 * Page Repository
 * 
 * @package   Repositories
 * @author    Den
 */
class PageRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(PageModel $model) {

        $this->model = $model;
        $this->countPerPage = 10;
    }

    public function getAllSettingsIndexPage($pageNumber) {
        return $this->model
                        ->where('visible', 1)
                        ->where('page_number', $pageNumber)
                        ->first();
    }

    public function getAllSettingsPage($pageNumber) {
        return $this->model
                        ->where('visible', 1)
                        ->where('page_number', $pageNumber)
                        ->first();
    }

    public function getPagesPaginated() {
        $count = $this->countPerPage;
        $pages = $this->model
                ->paginate($count);
        return ['pages' => $pages];
    }

    public function pageCount() {
        return ceil($this->model->count() / $this->countPerPage);
    }

    public function getFilteredPageData($limit) {
        if ($category == 0) {
            $category = NULL;
        }
        $pages = $this->model
                ->take($limit)
                ->get();
        return ['pages' => $pages, 'limit' => $limit];
    }

}
