---
title: 'Pivot'
module: Xot
type: reference
slug: pivot
description: 'https://github.com/larastan/larastan/issues/515'
tags: [migrato-da-txt, xot]
<<<<<<< HEAD
converted_from: _pivot.txt
=======
converted_from: pivot.txt
>>>>>>> 7f6cf6be (.)
created: 2026-08-24
updated: 2026-08-24
---

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
