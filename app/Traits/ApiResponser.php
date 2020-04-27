<?php  

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
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
		//haciendo uso de ordenar debe ser antes de la transformación 
		$collection = $this->sortData($collection);
		//aqui transformarmos la coleccion
		$collection = $this->transformData($collection,$transformer);
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
	protected function sortData(Collection $collection)
	{
		if(request()->has('sort_by')){
			$attribute = request()->sort_by;

			$collection = $collection->sortBy->{$attribute};
		}
		return $collection;
	}
	protected function transformData($data, $transformer)
	{
		$transformation = fractal($data,new $transformer);

		return $transformation->toArray();
	}
}

?>