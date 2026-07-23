#!/usr/bin/env python3
"""
HTML Structure Analyzer per visual parity
Confronta struttura HTML tra reference Italia Design Comuni e locale FixCity
"""

import re
import sys
from html.parser import HTMLParser
from collections import defaultdict
from typing import Dict, List, Set

class HTMLElement:
    def __init__(self, tag: str, attrs: Dict[str, str], depth: int):
        self.tag = tag
        self.attrs = attrs
        self.depth = depth
        self.children = []
        self.id = None
        self.classes = []
        
        for k, v in attrs:
            if k == 'id':
                self.id = v
            elif k == 'class':
                self.classes = v.split() if v else []

class HTMLStructureParser(HTMLParser):
    def __init__(self):
        super().__init__()
        self.elements = []
        self.depth = 0
        self.stack = []
        
    def handle_starttag(self, tag, attrs):
        if tag in ('script', 'style', 'link', 'meta', 'head', 'body'):
            return
            
        elem = HTMLElement(tag, attrs, self.depth)
        
        if self.stack:
            self.stack[-1].children.append(elem)
        
        self.stack.append(elem)
        self.depth += 1
        
    def handle_endtag(self, tag):
        if tag in ('script', 'style', 'link', 'meta', 'head', 'body'):
            return
            
        if self.stack and self.stack[-1].tag == tag:
            elem = self.stack.pop()
            if not self.stack:
                self.elements.append(elem)
            self.depth = max(0, self.depth - 1)

def analyze_structure(html: str) -> Dict:
    parser = HTMLStructureParser()
    try:
        parser.feed(html)
    except:
        pass
    
    tags = defaultdict(int)
    ids = []
    classes = []
    
    def traverse(elem):
        tags[elem.tag] += 1
        if elem.id:
            ids.append(f"#{elem.id}")
        classes.extend(elem.classes)
        for child in elem.children:
            traverse(child)
    
    for elem in parser.elements:
        traverse(elem)
    
    return {
        'tags': dict(tags),
        'ids': list(set(ids)),
        'classes': list(set(classes)),
        'total_elements': sum(tags.values())
    }

def compare_structures(ref: Dict, local: Dict) -> Dict:
    differences = {
        'missing_in_local': [],
        'extra_in_local': [],
        'tag_mismatch': [],
        'class_differences': [],
        'id_differences': []
    }
    
    ref_tags = set(ref['tags'].keys())
    local_tags = set(local['tags'].keys())
    
    missing = ref_tags - local_tags
    extra = local_tags - ref_tags
    
    for tag in missing:
        differences['missing_in_local'].append({
            'tag': tag,
            'count_in_ref': ref['tags'][tag]
        })
    
    for tag in extra:
        differences['extra_in_local'].append({
            'tag': tag,
            'count_in_local': local['tags'][tag]
        })
    
    for tag in ref_tags & local_tags:
        if ref['tags'][tag] != local['tags'][tag]:
            differences['tag_mismatch'].append({
                'tag': tag,
                'ref_count': ref['tags'][tag],
                'local_count': local['tags'][tag],
                'diff': ref['tags'][tag] - local['tags'][tag]
            })
    
    ref_classes = set(ref['classes'])
    local_classes = set(local['classes'])
    
    missing_classes = ref_classes - local_classes
    extra_classes = local_classes - ref_classes
    
    if missing_classes:
        differences['class_differences'].append({
            'type': 'missing',
            'classes': list(missing_classes)[:20]
        })
    
    if extra_classes:
        differences['class_differences'].append({
            'type': 'extra',
            'classes': list(extra_classes)[:20]
        })
    
    return differences

def main():
    if len(sys.argv) < 3:
        print("Usage: html_structure.py <reference_html> <local_html>")
        sys.exit(1)
    
    ref_file = sys.argv[1]
    local_file = sys.argv[2]
    
    with open(ref_file, 'r', encoding='utf-8') as f:
        ref_html = f.read()
    
    with open(local_file, 'r', encoding='utf-8') as f:
        local_html = f.read()
    
    ref_struct = analyze_structure(ref_html)
    local_struct = analyze_structure(local_html)
    
    print("=== REFERENCE STRUCTURE ===")
    print(f"Total elements: {ref_struct['total_elements']}")
    print(f"Tags: {sorted(ref_struct['tags'].items(), key=lambda x: -x[1])[:15]}")
    print(f"IDs: {ref_struct['ids'][:10]}")
    print(f"Classes: {ref_struct['classes'][:20]}")
    
    print("\n=== LOCAL STRUCTURE ===")
    print(f"Total elements: {local_struct['total_elements']}")
    print(f"Tags: {sorted(local_struct['tags'].items(), key=lambda x: -x[1])[:15]}")
    print(f"IDs: {local_struct['ids'][:10]}")
    print(f"Classes: {local_struct['classes'][:20]}")
    
    diffs = compare_structures(ref_struct, local_struct)
    
    print("\n=== DIFFERENCES ===")
    for key, value in diffs.items():
        if value:
            print(f"\n{key}:")
            for v in value[:5]:
                print(f"  {v}")

if __name__ == '__main__':
    main()