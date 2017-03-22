Feature: Configuration files
    In order to configure my application
    As a developer
    I need to be able to store configuration options in a file

    Scenario: Getting a configured option
        Given there is a configuration file
        And the option 'timezone' is configured to 'UTC'
        When I load the configuration file
        Then I should get 'UTC' as 'timezone' option

    Scenario: Getting a non-configured option with a default value
        Given there is a configuration file
        And the option 'timezone' is not yet configured
        When I load the configuration file
        Then I should get default value 'CET' as 'timezone' option

    Scenario: Setting a configuration option
        Given there is a configuration file
        And the option 'timezone' is configured to 'UTC'
        When I load the configuration file
        And I set the 'timezone' configuration option to 'GMT'
        Then I should get 'GMT' as 'timezone' option