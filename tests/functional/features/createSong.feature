Feature: Create song
  As a user using the setlists api
  I need to be able to create songs containing an unique name

  Scenario: Song is valid and can be created
    Given I want to create a song with title "Cool song" and id "d2efe5df-aaa1-4c06-9e6d-7215860a0a13"
    When I request the api service to create the song
    Then the song should have been created