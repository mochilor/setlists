Feature: Create and retrieve setlists
  As a user using the setlists api
  I need to be able to create and retrieve setlists


  Scenario: Setlists with one act can be created and retrieved by its id
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And I want to prepare some acts with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 0          | 1          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And I want to add the acts to some setlists with the following data:
      | id                                   | name          | description                  | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is the best one | 2019-01-01 |
    When I request the api service to create the setlist
    Then the api must return a response with code: 201
    And the api must show me the setlist if I request it by its id


  Scenario: Setlists with several acts can be created and retrieved by its id
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black     | 1          |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here | 1          |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell    | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And I want to prepare some acts with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 0          | 1          | 13080dc1-63f2-4770-aa76-683bdf22c5a6 |
      | 1          | 0          | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 |
      | 1          | 1          | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb |
      | 2          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And I want to add the acts to some setlists with the following data:
      | id                                   | name          | description                  | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is the best one | 2019-01-01 |

    When I request the api service to create the setlist
    Then the api must return a response with code: 201
    And the api must show me the setlist if I request it by its id


  Scenario: Setlists with several acts can be created and retrieved all together
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black     | 1          |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here | 1          |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell    | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And I want to prepare some acts with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 0          | 1          | 13080dc1-63f2-4770-aa76-683bdf22c5a6 |
      | 1          | 0          | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 |
      | 1          | 1          | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb |
      | 2          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And I want to add the acts to some setlists with the following data:
      | id                                   | name          | description                   | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | First Setlist | This Setlist is the first one | 2019-01-01 |
      | 30f1bab8-2eff-47be-b9ce-80c6d2f76d8c | Last Setlist  | This Setlist is the last one  | 2019-02-28 |

    When I request the api service to create the setlists
    Then the api must return a response with code: 201
    And the api must show me all the setlists if I request them


  Scenario: Setlists with several acts can be created and retrieved paginated
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black     | 1          |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here | 1          |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell    | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And I want to prepare some acts with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 0          | 1          | 13080dc1-63f2-4770-aa76-683bdf22c5a6 |
      | 1          | 0          | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 |
      | 1          | 1          | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb |
      | 2          | 0          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And I want to add the acts to some setlists with the following data:
      | id                                   | name           | description                    | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | First Setlist  | This Setlist is the first one  | 2019-01-01 |
      | c3a5a32f-0fbc-4642-99bf-e695832ab055 | Middle Setlist | This Setlist is the second one | 2019-09-05 |
      | 30f1bab8-2eff-47be-b9ce-80c6d2f76d8c | Last Setlist   | This Setlist is the last one   | 2019-02-28 |

    When I request the api service to create the setlists
    Then the api must return a response with code: 201
    And the api must be able to show me a list with setlists from: 0 to: 2
    And the api must be able to show me a list with setlists from: 3 to the end


  Scenario: Setlists with one act can be created and retrieved by its id
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And I want to prepare some acts with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 0          | 1          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And I want to add the acts to some setlists with the following data:
      | id                                   | name          | description                  | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is the best one | 2019-01-01 |
    When I request the api service to create the setlist
    Then the api must return a response with code: 201
    And the api must show me the setlist if I request it by its id


  Scenario: Setlists with no description can be created
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black     | 1          |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here | 1          |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell    | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And I want to prepare some acts with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 8          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 2          | 0          | 13080dc1-63f2-4770-aa76-683bdf22c5a6 |
      | 99         | 1          | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 |
      | 99         | 0          | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb |
      | 1          | 2          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And I want to add the acts to some setlists with the following data:
      | id                                   | name          | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | 2019-01-01 |

    When I request the api service to create the setlist
    Then the api must return a response with code: 201
    And the api must show me the setlist if I request it by its id


  Scenario: Setlists with repeated songs can not be created
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And I want to prepare some acts with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 0          | 1          | 45bf5e28-da2f-4207-bf67-466baa7af86e |
      | 1          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |

    And I want to add the acts to some setlists with the following data:
      | id                                   | name          | description                  | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Cool Setlist! | This Setlist is the best one | 2019-01-01 |
    When I request the api service to create the setlist
    Then the api must return a response with code: 500
    And the api must show me the setlist if I request it by its id
    And the api must not return any setlist when I request all the stored setlists


  Scenario: Setlists with non unique name can not be created
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

    And I want to add the acts to some setlists with the following data:
      | id                                   | name          | description                        | date       |
      | c3a5a32f-0fbc-4642-99bf-e695832ab055 | Cool Setlist! | This Setlist has a non unique name | 2022-10-11 |

    When I request the api service to create the setlist
    Then the api must return a response with code: 409


  Scenario: Setlist with too short name can not be created
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And I want to prepare some acts with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 0          | 1          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And I want to add the acts to some setlists with the following data:
      | id                                   | name | description                       | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | :)   | This Setlist has a too short name | 2019-01-01 |

    When I request the api service to create the setlist
    Then the api must return a response with code: 500
    And the api must not return any setlist when I request all the stored setlists


  Scenario: Setlist with too long name can not be created
    Given the following songs exists:
      | id                                   | title              | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday          | 1          |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven | 1          |

    And I want to prepare some acts with the following data:
      | act_number | song_order | song_id                              |
      | 0          | 0          | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |
      | 0          | 1          | 45bf5e28-da2f-4207-bf67-466baa7af86e |

    And I want to add the acts to some setlists with the following data:
      | id                                   | name                                                                                                                        | description                      | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. | This Setlist has a too long name | 2019-01-01 |

    When I request the api service to create the setlist
    Then the api must return a response with code: 500
    And the api must not return any setlist when I request all the stored setlists


  Scenario: Setlist with no acts can not be created
    Given I want to create an empty setlist with the following data:
      | id                                   | name | description                       | date       |
      | 9c5999a5-2468-45ba-ae77-3965fc385519 | :)   | This Setlist has a too short name | 2019-01-01 |

    When I request the api service to create the setlist
    Then the api must return a response with code: 500
    And the api must not return any setlist when I request all the stored setlists


  Scenario: Requesting a Setlist that has not been created yet returns an error
    Given no Setlist exist
    When I request the api to show me the setlist with id: "9c5999a5-2468-45ba-ae77-3965fc385519"
    Then the api must return a response with code: 404
    And the api must not return any setlist when I request all the stored setlists