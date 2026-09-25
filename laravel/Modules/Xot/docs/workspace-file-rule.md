# Workspace File Naming Rule

## Rule

**Every module MUST have exactly ONE `.code-workspace` file.**

The filename is derived from the **Git remote** of the nested repo, not from
the module class name. Run this inside the module folder:

```bash
cd laravel/Modules/<Name> && git remote -v
```

The remote url basename gives the canonical name:

1. take the remote basename without `.git` (es. `module_xot_fila5`)
2. strip the `_fila<number>` suffix (es. `module_xot`)
3. prefix with `_` and append `.code-workspace` → `_module_xot.code-workspace`

So `module_xot_fila5.git` → `_module_xot.code-workspace`.

## Examples

| Remote basename | Correct Filename | Incorrect Filenames |
|-----------------|-----------------|---------------------|
| `module_xot_fila5` | `_module_xot.code-workspace` | `_module_xot_fila5.code-workspace`, `_xot.code-workspace` |
| `module_activity_fila5` | `_module_activity.code-workspace` | `_module_activity_fila5.code-workspace`, `_activity.code-workspace` |
| `module_job_fila5` | `_module_job.code-workspace` | `_module_job_fila5.code-workspace`, `_job_base.code-workspace` |
| `theme_zero_fila5` | `_theme_zero.code-workspace` | `_theme_zero_fila5.code-workspace`, `zero.code-workspace` |

## Rationale

1. **Consistency**: One module = one workspace file with predictable naming
2. **Discoverability**: Developers can immediately find the workspace file for any module
3. **IDE Configuration**: Each module's VSCode workspace settings are contained in a single, clearly-identified file
4. **Version Control**: Prevents confusion about which workspace file is authoritative
5. **Il nome deriva dal remote**: il suffisso `_fila<number>` dipende dal deployment e non deve comparire nel nome del workspace

## Common Mistakes

### ❌ Wrong: Multiple workspace files in one module

```
Modules/Xot/
  _module_xot.code-workspace  # ✓ Correct
  _activity.code-workspace    # ✗ Wrong - belongs to Activity module
```

### ❌ Wrong: Workspace file with wrong name

```
Modules/Job/
  _module_job_fila5.code-workspace  # ✗ Wrong - keeps the _fila suffix
  _job_workspace.code-workspace     # ✗ Wrong - not derived from remote
```

### ✅ Correct

```
Modules/Job/
  _module_job.code-workspace  # ✓ Correct (remote: module_job_fila5)
```

## Cross-Module Dependencies

Some modules may reference other modules' workspace files for development workflows, but each module's directory should contain ONLY its own workspace file.

Example: A developer might have a root workspace that includes multiple modules, but that file belongs in the project root, not inside individual module directories.

## Enforcement

- Check for multiple `.code-workspace` files during code review
- Use `find Modules -name "*.code-workspace"` to audit compliance
- Remove any workspace files that don't match the naming convention

## Related Documentation

- [Module Structure Standards](module-structure.md)
- [Development Environment Setup](development-environment.md)
