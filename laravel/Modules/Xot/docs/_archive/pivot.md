//https://github.com/larastan/larastan/issues/515

/**
 * @extends JsonResource<\App\User>
*/
class UserResource extends JsonResource
{
    // Other parts of the resource omitted
    public function toArray($request)
    {
        /** @var \App\User **/
        $user = $this;
        return [
              "time_to_live" => $this->whenPivotLoaded("table", function () use($user) {
                return $user->getRelationValue("pivot")->time_to_live;  // This is the line 45
            })
         ];
      }
}

 //return $this->pivot->time_to_live;  // This is the line 45

getRelationValue("pivot")



$dpia = request()->route('dpias');
$dpia = app('request')->route('dpias');
///////////////////////
/**
 * @property int $id
 */
class MyCustomModel extends Model {}
////////////////////

getModel - Builder
<<<<<<<< HEAD:laravel/Modules/Xot/docs/_archive/pivot.md
paginate - Builder
========
paginate - Builder
>>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php):laravel/Modules/Xot/pivot.md
