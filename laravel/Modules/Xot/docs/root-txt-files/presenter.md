---
title: 'Presenter'
module: Xot
type: reference
slug: presenter
description: "$acceptHeader=$request->header('Accept');"
tags: [migrato-da-txt, xot]
converted_from: presenter.txt
created: 2026-08-24
updated: 2026-08-24
---

$acceptHeader=$request->header('Accept');

if(in_array($acceptHeader,['application/json','application/xml'])){
    ...
    return response()->json($data,200);
}
...

---------------- XMLPRESENTERS -----------------
$xml=new \SimpleXMLElement('<posts/>');
foreach($posts as $post){
    $xmlItem=$xml->addChild('post');
    $xmlItem->addChild('id',$post->id);
    ...
}
return $xml->asXML;


$posts =Post::orderBy('id','desc')->paginate(10);

foreach($posts->items('data') as $item)
