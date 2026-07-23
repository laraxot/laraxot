# Documentation Maintenance Strategy

## Purpose
This document outlines the strategy for maintaining project documentation in the 'docs' directory.

## Documentation Files
1. `laraxot.md`: Detailed technical documentation of the Laraxot framework
2. `project.md`: High-level project overview
3. `documentation_strategy.md`: Guidelines for documentation maintenance

## Principles
- Always check documentation before making changes
- Update documentation immediately after code modifications
- Maintain clarity and conciseness
- Use markdown for formatting
- Include technical details and context

## Update Workflow
1. **Before Code Changes**
   - Review relevant documentation sections
   - Check for existing documentation
   - Prevent duplicate or conflicting information

2. **During Code Changes**
   - Update technical documentation in real-time
   - Add comments explaining complex implementations
   - Update version history and changelog

3. **After Code Changes**
   - Verify documentation accuracy
   - Add examples or code snippets
   - Update version numbers if necessary

## Best Practices
- Use clear, technical language
- Include code examples
- Document design decisions
- Explain the "why" behind implementations
- Keep documentation DRY (Don't Repeat Yourself)

## Maintenance Checklist
- [ ] Update technical documentation
- [ ] Verify code examples
- [ ] Check for outdated information
- [ ] Ensure consistency across files
- [ ] Review for clarity and completeness
- [x] Run getFormSchema method check for XotBaseResource classes
  - 46 classes identified as missing getFormSchema method
  - Implement getFormSchema in all XotBaseResource subclasses
  - Ensure consistent form schema implementation

## Tools and Automation
- Use PHPStan reports for technical documentation
- Integrate documentation checks in CI/CD pipeline
- Use `update_docs.sh` script for documentation maintenance
  - Check existing documentation before changes
  - Log documentation updates
  - Prevent duplicate information
- Consider automated documentation generation tools

## Versioning
- Include version number in documentation
- Maintain a changelog
- Mark deprecated features
- Document migration paths for breaking changes

## Review Frequency
- Weekly: Quick documentation review
- Monthly: Comprehensive documentation audit
- After major feature releases: Extensive documentation update

## Contribution Guidelines
- All team members responsible for documentation
- Use pull requests for documentation changes
- Peer review documentation updates
- Encourage continuous improvement

## Contact
For documentation-related questions, contact the project lead.
