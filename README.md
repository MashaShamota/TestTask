# TestTask
It is a basic implementation, but for "real life" it needs more strategic API structure and different improvements should be done:

1. Classes autoload
2. Logging and proper error handling
3. Based on API consumers gat response formats and status codes for not positive scenarios.
4. Unit Tests
5. SQL Queries profiling on big data sets. Add indexation if needed
  5.1. In case of problems with grouping on mysql side, grouping and calculations can be moved to php.
6. For event types (click, play, view) use enum type in database (in case of MySQL)
7. Depends on situation and database, for boosting performance pivot table can be created or cache, etc.
