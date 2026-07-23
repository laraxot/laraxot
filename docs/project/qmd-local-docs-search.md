---
title: "QMD — local docs search"
type: concept
tags: [qmd, second-brain, search, mcp, wiki]
created: 2026-07-06
updated: 2026-07-06
qmd: "qmd install setup mcp bm25 embeddings second brain local docs search"
related:
  - ../wiki/concepts/second-brain-operating-model.md
  - ../wiki/concepts/second-brain-search-stack.md
  - ../../bashscripts/docs/llm-wiki-qmd-workflow.md
---

# QMD — local docs search

QMD (`@tobilu/qmd` on npm) is the search engine behind the project's second brain
(`docs/wiki/` / `bashscripts/ai/wiki/`). It indexes markdown with BM25 (lexical) and,
optionally, local embeddings (semantic) + reranking — all on-device, no cloud calls.

## Install

```bash
sudo npm install -g --allow-scripts=better-sqlite3,node-llama-cpp,tree-sitter-go,tree-sitter-python,tree-sitter-rust,tree-sitter-typescript,tree-sitter-javascript @tobilu/qmd
```

`--allow-scripts` is required: `better-sqlite3` needs its native build/prebuild step,
and `node-llama-cpp` needs its postinstall to fetch the llama.cpp runtime used for
embeddings/reranking. Without it, `qmd embed`/`qmd query` fail even though `qmd search`
(pure BM25) still works.

## Project wrapper

Always invoke through `bashscripts/docs/llm-wiki-qmd.sh`, not the bare `qmd` binary —
it pins a project-local cache (`XDG_CACHE_HOME=~/.cache/qmd-cache`,
`XDG_CONFIG_HOME=~/.cache/qmd-config`) so the index/collections don't collide with
other projects on the same machine, and forces `QMD_LLAMA_GPU=off` (no GPU in this
container).

```bash
bash bashscripts/docs/llm-wiki-qmd.sh status
bash bashscripts/docs/llm-wiki-qmd.sh search "<query>"
bash bashscripts/docs/llm-wiki-qmd.sh embed
```

## Collection

The indexed collection is named `wiki`, pointed at the **real** directory
`bashscripts/ai/wiki` (not `docs/wiki`, which is a tree of symlinks into it —
`find`/qmd's default glob does not follow symlinks, so indexing `docs/wiki` directly
only picks up the two real files at its root).

```bash
bash bashscripts/docs/llm-wiki-qmd.sh collection add bashscripts/ai/wiki --rename wiki
```

## Embeddings

`qmd embed` downloads `embeddinggemma-300M` (~330 MB, one-time) and computes vectors
per chunk. On CPU-only hosts this is slow (single digit docs/minute); let it run in
the background. BM25 (`qmd search`) is fully functional without it — embeddings only
add semantic ranking to `qmd query`/`qmd vsearch`.

## MCP

`qmd mcp` starts an MCP server (stdio) exposing the same search/query/get tools.
Registered in `.mcp.json`:

```json
"qmd": {
  "type": "stdio",
  "command": "qmd",
  "args": ["mcp"],
  "env": {
    "XDG_CACHE_HOME": "${HOME}/.cache/qmd-cache",
    "XDG_CONFIG_HOME": "${HOME}/.cache/qmd-config",
    "QMD_LLAMA_GPU": "off"
  }
}
```

## See also

- [Second Brain Operating Model](../wiki/concepts/second-brain-operating-model.md)
- [Second brain — search stack](../wiki/concepts/second-brain-search-stack.md)
- [llm-wiki-qmd workflow](../../bashscripts/docs/llm-wiki-qmd-workflow.md)
