---
title: 'Scope'
module: Xot
type: reference
slug: scope
description: 'Calling some fields from a specific table in a more organized way - Laravel'
tags: [migrato-da-txt, xot]
converted_from: scope.txt
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
