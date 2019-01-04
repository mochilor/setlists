Feature: Update setlists
  As a user using the setlists api
  I need to be able to update existing setlists


  Scenario: All fields from a Setlist can be updated
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And The songs are sorted in the following acts:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And The acts belong to a setlist with the following data:
      | id                                   | name          | description                  | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is the best one | 2019-01-01 |

    And I want to update the setlist with the following data:
      | id                                   | name          | description                    | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Nice Setlist! | This Setlist has been modified | 2012-10-01 |
    When I request the api service to update the setlist
    Then the api must return a response with code: 200
    And the api must show me the setlist if I request it by its id


  Scenario: Acts from a Setlist can be updated
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here | 1          |

    And The songs are sorted in the following acts:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And The acts belong to a setlist with the following data:
      | id                                   | name          | description                  | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is the best one | 2019-01-01 |

    And I want to update the acts for the first setlist with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |
      | 0          | 1          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 |
    When I request the api service to update the setlist
    Then the api must return a response with code: 200
    And the api must show me the setlist if I request it by its id


  Scenario: Setlist can not be updated when provided songs does not exist
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And The songs are sorted in the following acts:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And The acts belong to a setlist with the following data:
      | id                                   | name          | description                  | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is the best one | 2019-01-01 |

    And I want to update the acts for the first setlist with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |
      | 1          | 0          | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 |
    When I request the api service to update the setlist
    Then the api must return a response with code: 500
    And the api must show me the setlist if I request it by its id


  Scenario: Setlist can not be updated when provided songs have invalid id
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And The songs are sorted in the following acts:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And The acts belong to a setlist with the following data:
      | id                                   | name          | description                  | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is the best one | 2019-01-01 |

    And I want to update the acts for the first setlist with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |
      | 1          | 0          | bc0bd9a8-0fe4-49a4-aee0-invalid!     |
    When I request the api service to update the setlist
    Then the api must return a response with code: 500
    And the api must show me the setlist if I request it by its id


  Scenario: Acts from a Setlist can not be updated if they have repeated songs
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here | 1          |

    And The songs are sorted in the following acts:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And The acts belong to a setlist with the following data:
      | id                                   | name          | description                  | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is the best one | 2019-01-01 |

    And I want to update the acts for the first setlist with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |
      | 0          | 1          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |
    When I request the api service to update the setlist
    Then the api must return a response with code: 500
    And the api must show me the setlist if I request it by its id


  Scenario: Acts from a Setlist can not be updated if they have no songs
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here | 1          |

    And The songs are sorted in the following acts:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And The acts belong to a setlist with the following data:
      | id                                   | name          | description                  | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is the best one | 2019-01-01 |

    And I want to update the acts for the first setlist with empty data
    When I request the api service to update the setlist
    Then the api must return a response with code: 500
    And the api must show me the setlist if I request it by its id


  Scenario: Acts from a Setlist can not be updated if provided name is not unique
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

    And I want to update the setlist with the following data:
      | id                                   | name                  | description                   | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Another Cool Setlist! | This Setlist is the first one | 2019-01-01 |
    When I request the api service to update the setlist
    Then the api must return a response with code: 409
    And the api must show me the setlist if I request it by its id


  Scenario: Setlist can not be updated when provided name is too short
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And The songs are sorted in the following acts:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And The acts belong to a setlist with the following data:
      | id                                   | name          | description           | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is valid | 2019-01-01 |

    And I want to update the setlist with the following data:
      | id                                   | name | description                               | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Hi   | This Setlist has an invalid name modified | 2012-10-01 |
    When I request the api service to update the setlist
    Then the api must return a response with code: 500
    And the api must show me the setlist if I request it by its id


  Scenario: Setlist can not be updated when provided name is too long
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And The songs are sorted in the following acts:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And The acts belong to a setlist with the following data:
      | id                                   | name          | description           | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is valid | 2019-01-01 |

    And I want to update the setlist with the following data:
      | id                                   | name                                                                                                                        | description                               | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. | This Setlist has an invalid name modified | 2012-10-01 |
    When I request the api service to update the setlist
    Then the api must return a response with code: 500
    And the api must show me the setlist if I request it by its id


  Scenario: Setlist can be updated even if no description is provided
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And The songs are sorted in the following acts:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 1          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And The acts belong to a setlist with the following data:
      | id                                   | name          | description                  | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is the best one | 2019-01-01 |

    And I want to update the setlist with the following data:
      | id                                   | name          | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Nice Setlist! | 2012-10-01 |
    When I request the api service to update the setlist
    Then the api must return a response with code: 200
    And the api must show me the setlist if I request it by its id


  Scenario: Updating a Setlist that has not been created yet returns an error
    Given no Setlist exist
    And I want to update the setlist with the following data:
      | id                                   | name          | description                    | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Nice Setlist! | This Setlist has been modified | 2012-10-01 |

    When I request the api service to update the setlist
    Then the api must return a response with code: 404
    And the api must not return any setlist when I request all the stored setlists