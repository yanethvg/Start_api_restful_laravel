<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformedInput = [];
        foreach ($request->request->all() as $input => $value) {
            //con esto se sabra que si manda titulo en realidad es name
            //transformación al campo original
            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }
        //se reeemplaza en el request por el valor dado en el transformer
        $request->replace($transformedInput);
        //se va a operar en la respuesta
        $response = $next($request);

        if (isset($response->exception) && $response->exception instanceof ValidationException) 
        {
            $data = $response->getData();
            //nueva variable con los errores de cada transformación

            $transformedErrors =[];
            foreach ($data->error as $field => $error) {
                $transformedField = $transformer::transformedAttribute($field);
                //sustitucíon pcon el atributo transformer
                $transformedErrors[$transformedField] = str_replace($field,$transformedField,$error);
            }
            $data->error = $transformedErrors;

            $response->setData($data);

        }
        return $response;
    }
}
