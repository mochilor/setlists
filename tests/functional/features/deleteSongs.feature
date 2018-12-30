Feature: Delete songs
  As a user using the setlists api
  I need to be able to delete songs

  Scenario: Song can be deleted
    Given the following song exists:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |

    When I request the api service to delete the song
    Then the api must return a response with code: 200
    And the api must not return any song when I request all the stored songs


  Scenario: Song that does not exist can not be deleted
    Given no Song exist
    When I request the api service to delete the song with id: "d2efe5df-aaa1-4c06-9e6d-7215860a0a13"
    Then the api must return a response with code: 404


  Scenario: Deleting a Song with an invalid id returns an error
    Given the following song exists:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |

    When I request the api service to delete the song with id: "d2efe5df-aaa1-4c06-9e6d-non-valid!"
    Then the api must return a response with code: 500


  Scenario: Song that belongs to at least one setlist can not be deleted
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And The songs are sorted in the following acts:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 0          | 1          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And The acts belong to a setlist with the following data:
      | id                                   | name          | description                      | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is the original one | 2019-01-01 |

    When I request the api service to delete the song with id: "d2efe5df-aaa1-4c06-9e6d-7215860a0a13"
    Then the api must return a response with code: 500
    And the api must show me any of the songs if I request them by their id