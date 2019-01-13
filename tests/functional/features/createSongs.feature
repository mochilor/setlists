Feature: Create and retrieve songs
  As a user using the setlists api
  I need to be able to create and retrieve songs

  
  Scenario: Songs can be created and retrieved separately
    Given I want to create songs with values:
      | id                                   | title     |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here |

    When I request the api service to create the songs
    Then the api must return a response with code: 201
    And the api must show me any of the songs if I request them by their id


  Scenario: Songs can be created and retrieved all together
    Given I want to create songs with values:
      | id                                   | title     |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven |

    When I request the api service to create the songs
    Then the api must return a response with code: 201
    And the api must show me all the songs if I request them
    And the songs in the list will be these ones:
      | id                                   | title     |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday |


  Scenario: Songs can be created and retrieved all together paginated
    Given I want to create songs with values:
      | id                                   | title     |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven |

    When I request the api service to create the songs
    Then the api must return a response with code: 201
    And the api must be able to show me a list with songs from: 0 to: 3
    And the songs in the list will be these ones:
      | id                                   | title     |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven |
    And the api must be able to show me a list with songs from: 3 to the end
    And the songs in the list will be these ones:
      | id                                   | title     |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday |


  Scenario: Songs can be created and retrieved all together paginated and filtered by title
    Given I want to create songs with values:
      | id                                   | title     |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven |
      | 87fd2aa5-41f1-4dac-8974-b1bbab8970ec | My way |

    When I request the api service to create the songs
    Then the api must return a response with code: 201
    And the api must be able to show me a list with songs from: 0 to: 2 filtered by the word: "way"
    And the songs in the list will be these ones:
      | id                                   | title     |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell |
      | 87fd2aa5-41f1-4dac-8974-b1bbab8970ec | My way |


  Scenario: Songs can be created and retrieved all together filtered by title
    Given I want to create songs with values:
      | id                                   | title     |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven |
      | 87fd2aa5-41f1-4dac-8974-b1bbab8970ec | My way |

    When I request the api service to create the songs
    Then the api must return a response with code: 201
    And the api must be able to show me a list with songs filtered by the word: "way"
    And the songs in the list will be these ones:
      | id                                   | title     |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell |
      | 87fd2aa5-41f1-4dac-8974-b1bbab8970ec | My way |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven |


  Scenario: Song without id can not be created
    Given I want to create songs with values:
      | title     |
      | Yesterday |

    When I request the api service to create the songs
    Then the api must return a response with code: 500
    And the api must not return any song when I request all the stored songs


  Scenario: Song with invalid id can not be created
    Given I want to create songs with values:
      | id                                | title     |
      | d2efe5df-aaa1-4c06-9e6d-non-valid | Yesterday |

    When I request the api service to create the songs
    Then the api must return a response with code: 500
    And the api must not return any song when I request all the stored songs


  Scenario: Song without title can not be created
    Given I want to create songs with values:
      | id                                   |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |

    When I request the api service to create the songs
    Then the api must return a response with code: 500
    And the api must not return any song when I request all the stored songs


  Scenario: Song with unique title and id can be created
    Given the following song exists:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |

    And I want to create songs with values:
      | id                                   | title     |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Tomorrow  |

    When I request the api service to create the songs
    Then the api must return a response with code: 201
    And the api must show me all the songs if I request them


  Scenario: Song with non unique title can not be created
    Given the following song exists:
      | id                                   | title     | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday | 1          |

    And I want to create songs with values:
      | id                                   | title     |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Yesterday |

    When I request the api service to create the songs
    Then the api must return a response with code: 409


  Scenario: Song with non unique id can not be created
    Given the following song exists:
      | id                                   | title      | is_visible |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday  | 1          |

    And I want to create songs with values:
      | id                                   | title      |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Wonderwall |

    When I request the api service to create the songs
    Then the api must return a response with code: 409


  Scenario: Song with too short title can not be created
    Given I want to create songs with values:
      | id                                   | title     |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | 12        |

    When I request the api service to create the songs
    Then the api must return a response with code: 500
    And the api must not return any song when I request all the stored songs


  Scenario: Song with too long title can not be created
    Given I want to create songs with values:
      | id                                   | title     |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. |

    When I request the api service to create the songs
    Then the api must return a response with code: 500
    And the api must not return any song when I request all the stored songs


  Scenario: Requesting a Song that has not been created yet returns an error
    Given no Song exist
    When I request the api to show me the song with id: "d2efe5df-aaa1-4c06-9e6d-7215860a0a13"
    Then the api must return a response with code: 404
    And the api must not return any song when I request all the stored songs


  Scenario: Requesting a Song with an invalid id returns an error
    Given the following song exists:
      | id                                | title     |
      | d2efe5df-aaa1-4c06-9e6d-non-valid | Yesterday |

    When I request the api to show me the song with id: "d2efe5df-aaa1-4c06-9e6d-invalid"
    Then the api must return a response with code: 500