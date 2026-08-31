---
title: "Scope"
type: reference
tags: [wiki, no-frontmatter-fix]
created: 2026-08-24
updated: 2026-08-24
---

Calling some fields from a specific table in a more organized way - Laravel

If you want to call some fields from a specific table in a more organized way, you can write this function in Model :-

public function scopeDoctorFields($query)
{
$query->select('name');
}

then call function inside Controller :-

$doctors = User::doctorFields()->paginate(100);

--------------------------------------------------------------------------------------
