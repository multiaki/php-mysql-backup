Feature: Backups on local system
  In order to backup my projects
  As an administrator
  I need to save my projects in zipped archives locally

  Scenario: Backup project with two directories in paths
    Given I run the app using the project "test-1"
    Then  I should see a folder named "test-1" in the backups directory
    And   The backup folder of project "test-1" should contain 1 file of type "zip"
    And   The log file exists

  Scenario: Define a base path for projects
    Given I run the app using the project "test-2"
    Then  The backup folder of project "test-2" should contain 1 file of type "zip"

  Scenario: Test importing database backup file
    Given I run the sql file "test_3.sql" to the empty database "test_3"
    Then  There is a row with "name" "Dimitrios" in the "users" table

    @wip
  Scenario: Test database backup file is saved
    Given I run the sql file "test_3.sql" to the empty database "test_3"
    And   I run the app using the project "test-3"
    Then  The backup folder of project "test-3" should contain 1 file of type "zip"

  Scenario: Test importing database backup file
    Given I run the sql file "test_3.sql"
    And   I run the app using the project "test-3"
    And   I extract the generated backup file
    Then  I truncate the table "test_3"
    And   There is not a row with "name" "Dimitrios" in the "users" table
    And   I run the extracted file "test_3.sql"
    Then  There is a row with "name" "Dimitrios" in the "users" table

    # http://stackoverflow.com/questions/19751354/how-to-import-sql-file-in-mysql-database-using-php