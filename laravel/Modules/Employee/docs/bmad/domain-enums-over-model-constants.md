---
title: "Employee domain enums"
module: Employee
type: decision
updated: 2026-10-06
---

# Employee domain enums

Finite business vocabularies are represented by backed enums:

- AbsenceRequestStatusEnum
- AbsenceRequestTypeEnum
- TimeEntryStatusEnum

The database continues to store the same strings. Use ->value at query,
factory, form and persistence boundaries. Technical constants such as paths,
URLs, cache keys and numeric limits remain constants because they are not
domain states.
