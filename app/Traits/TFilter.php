<?php

namespace App\Traits;

use App\Helpers\Utilidade;

trait TFilter
{
	public function getRequest(){
		return request();
	}

	public function getTotalPage(){

		$pagination = request()->input('pagination');
        $pagination = ((!$pagination || !is_numeric($pagination)) ? 50 : $pagination);

		return $pagination;
	}

	public function getCurrentPage(){

		$current_page = request()->input('page');

		return $current_page;
	}

	public function getSearch(){

		$search = request()->input('search');

		if(empty($search))return null;

		return $search;
	}

	public function getFilters(){

		$filters = request()->input('filter');

		$allowed_filter = [
			'created_at' => [],
    ];

    foreach ($allowed_filter as $key => $value) {
      $allowed_filter[$key] = (isset($filters[$key]) ? $filters[$key] : $value);

			switch ($key) {

				case 'created_at':

					if(!is_array($allowed_filter[$key])){
						$allowed_filter[$key] = [];
					}

					if(count($allowed_filter[$key]) != 2){
						$hoje = Utilidade::toDate(null,'Y-m-d H:i:s','Y-m-d');
						$ontem = Utilidade::dateAdd(-120,$hoje,'D');
						$amanha = Utilidade::dateAdd(30,$hoje,'D');
						$allowed_filter[$key] = [$ontem,$amanha];
					}
					else{
						$allowed_filter[$key][0] = Utilidade::convertDate($allowed_filter[$key][0]);
						$allowed_filter[$key][1] = Utilidade::convertDate($allowed_filter[$key][1]);
					}
				break;
			}

    }

    return $allowed_filter;
	}
}
