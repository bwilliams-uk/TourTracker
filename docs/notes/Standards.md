- Each 'Entity' object can only have knowledge of properties stored in its respective database table.

- Repositories and Indexes must be constructed with one parameter (PDO $pdo)

- Each 'Primary' Service is responsible for one entity type only and is constructed with (a) a repository object that manages CRUD operations on a single instance of each entity. (b) an index object for identifying instances of that entity which match a specified criteria.

- Controllers must only modify the model via a service class. No direct access to repositories.

- Controllers must not have any knowledge of other controllers. If you need to reuse code then this is a sign you are missing an important service class.