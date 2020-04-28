<?php  

namespace App\Traits;


use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
//use Illuminate\Database\Eloquent\Collection;

trait ApiResponser
{
	private function successResponse($data, $code)
	{
		return response()->json($data,$code);
	}

	protected function errorResponse($message,$code)
	{
		return response()->json(['error' => $message,'code' => $code], $code);
	}

	protected function showAll(Collection $collection,$code = 200)
	{
		if($collection->isEmpty()){
			return $this->successResponse(['data' => $collection], $code);
		}
		//aqui rescatamos que tipo de transformador
		$transformer = $collection->first()->transformer;
		//filtrar antes de ordenar
		$collection = $this->filterData($collection,$transformer);
		//haciendo uso de ordenar debe ser antes de la transformación 
		$collection = $this->sortData($collection,$transformer);
		//paginando la información
		$collection = $this->paginate($collection);
		//aqui transformarmos la coleccion
		$collection = $this->transformData($collection,$transformer);
		//utilizando el metodo de cache
		$collection = $this->cacheResponse($collection);
		//ya incluye data en la respuesta
		return $this->successResponse($collection,$code);
	}

	protected function showOne(Model $instance,$code = 200)
	{
		//aqui rescatamos que tipo de transformador
		$transformer = $instance->transformer;
		//aqui transformarmos la coleccion
		$instance= $this->transformData($instance,$transformer);

		return $this->successResponse($instance,$code);
	}
	protected function showMessage($message,$code = 200)
	{
		return response()->json(['data' => $message,'code' => $code], $code);
	}
	protected function sortData(Collection $collection, $transformer)
	{
		if(request()->has('sort_by')){
			$attribute = $transformer::originalAttribute(request()->sort_by);

			$collection = $collection->sortBy->{$attribute};
		}
		return $collection;
	}
	protected function filterData(Collection $collection, $transformer)
	{
		foreach (request()->query() as $query => $value) {
			$attribute = $transformer::originalAttribute($query);

			if (isset($attribute, $value)) {
				$collection = $collection->where($attribute, $value);
			}
		}

		return $collection;
	}
	protected function paginate(Collection $collection)
	{
		//aplicando para permitir el tamaño de pagina personalizado
		$rules = [
			'per_page' => 'integer|min:2|max:50'
		];

		Validator::validate(request()->all(), $rules);
		//paginador que tiene en cuenta la pagina actual
		$page = LengthAwarePaginator::resolveCurrentPage();

		$perPage = 15;
		//validando que se reciba el per_page
		if (request()->has('per_page')) {
			$perPage = (int) request()->per_page;
		}

		$results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

		$paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
			'path' => LengthAwarePaginator::resolveCurrentPath(),
		]);
		//para no eliminar los demas parametros para ordenar o filtrar
		$paginated->appends(request()->all());

		return $paginated;
	}
	protected function transformData($data, $transformer)
	{
		$transformation = fractal($data,new $transformer);

		return $transformation->toArray();
	}
	protected function cacheResponse($data)
	{
		//recibe un array no una collection
		//conocer la url actual
		$url = request()->url();
		//todos los parametros que hay en la URL
		$queryParams = request()->query();
		//ordena un array dependiendo de la clave
		ksort($queryParams);
		//recibe un array de los parametros ordenados
		$queryString = http_build_query($queryParams);
		//esta es la url completa
		$fullUrl = "{$url}?{$queryString}";
		//utilizar el fasa de cache recibe la url,tiempo, 
		return Cache::remember($fullUrl, 15/60, function() use($data) {
			return $data;
		});
	}
	
}

?>