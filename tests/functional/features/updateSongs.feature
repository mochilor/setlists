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
    Then the api must return a response with code: 200
    And the song should be updated


  Scenario: Incomplete data does not allow to update a Song
    Given the following song exists:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |

    And I want to change its data to the following values:
      | id                                   | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | 0          |

    When I request the api service to update the song
    Then the api must return a response with code: 500
    And the existent songs should be exactly like:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |


  Scenario: Updating a song with a non unique title is not allowed
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 0          |

    Given I want to update a song with the following values:
      | id                                   | title               | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Stairway to Heaven  | 1          |

    When I request the api service to update the song
    Then the api must return a response with code: 409
    And the existent songs should be exactly like:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 0          |


  Scenario: Updating a non existing Song returns an error
    Given I want to update a song with the following values:
      | id                                   | title               | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Stairway to Heaven  | 1          |

    When I request the api service to update the song
    Then the api must return a response with code: 404


  Scenario: Updating a Song with an invalid id returns an error
    Given I want to update a song with the following values:
      | id                                | title               | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-non-valid | Stairway to Heaven  | 1          |

    When I request the api service to update the song
    Then the api must return a response with code: 500


  Scenario: Updating a song with an empty title is not allowed
    Given the following song exists:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |

    And I want to change its data to the following values:
      | id                                   | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |  0         |

    When I request the api service to update the song
    Then the api must return a response with code: 500
    And the existent songs should be exactly like:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |


  Scenario: Updating a song with a too short title is not allowed
    Given the following song exists:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |

    And I want to change its data to the following values:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | AB        | 1          |

    When I request the api service to update the song
    Then the api must return a response with code: 500
    And the existent songs should be exactly like:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |


  Scenario: Updating a song with a too long title is not allowed
    Given the following song exists:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |

    And I want to change its data to the following values:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. | 1          |

    When I request the api service to update the song
    Then the api must return a response with code: 500
    And the existent songs should be exactly like:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |