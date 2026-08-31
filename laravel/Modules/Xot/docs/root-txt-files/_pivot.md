---
title: "Pivot"
type: reference
status: active
created: 2026-08-27
updated: 2026-08-27
note: "Convertito da _pivot.txt (documento) da convert-docs-txt-to-md.py."
---

# Pivot

https://github.com/larastan/larastan/issues/515

**
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
