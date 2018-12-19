Feature: Update songs
  As a user using the setlists api
  I need to be able to update songs

  Scenario: Song can be updated
    Given the following song exists:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |

    And I want to change its data to the following values:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Tomorrow  | 0          |

    When I request the api service to update the song
    Then the song should be updated

  Scenario: Incomplete data does not allow to update a Song
    Given the following song exists:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |

    And I want to change its data to the following values:
      | id                                   | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | 0          |

    When I request the api service to update the song
    Then the api must return an error response with code: 500
    And the existent songs should be exactly like:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |
