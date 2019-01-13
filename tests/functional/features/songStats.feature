Feature: Get song statistics
  As a user using the setlists api
  I need to be able to retrieve statistics relative to songs


  Scenario: Retrieving the setlists to which a song belongs
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here | 1          |

    And The songs are sorted in the following acts:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And The acts belong to some setlists with the following data:
      | id                                   | name                  | description                    | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist!         | This Setlist is the first one  | 2019-01-01 |
      | 30f1bab8-2eff-47be-b9ce-80c6d2f76d8c | Another Cool Setlist! | This Setlist is the second one | 2019-01-02 |

    When I request the api service to show me the setlists to which the song with id: "d2efe5df-aaa1-4c06-9e6d-7215860a0a13" belongs
    Then the api must return a response with code: 200
    And the api must return the following setlists:
      | id                                   | name                  | description                    | date       |
      | 30f1bab8-2eff-47be-b9ce-80c6d2f76d8c | Another Cool Setlist! | This Setlist is the second one | 2019-01-02 |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist!         | This Setlist is the first one  | 2019-01-01 |


  Scenario: Requesting the setlists to which a song belongs with an invalid id returns an error
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here | 1          |

    And The songs are sorted in the following acts:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And The acts belong to some setlists with the following data:
      | id                                   | name                  | description                    | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist!         | This Setlist is the first one  | 2019-01-01 |

    When I request the api service to show me the setlists to which the song with id: "d2efe5df-aaa1-4c06-9e6d-invalid" belongs
    Then the api must return a response with code: 500


  Scenario: Requesting the setlists to which a song belongs with a non existent song id returns an error
    Given no Song exist
    When I request the api service to show me the setlists to which the song with id: "d2efe5df-aaa1-4c06-9e6d-7215860a0a13" belongs
    Then the api must return a response with code: 404