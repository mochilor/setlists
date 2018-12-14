Feature: Handle songs
  As a user using the setlists api
  I need to be able to create, retrieve, update and remove songs

  Scenario: Songs can be created and retrieved separately
    Given I want to create songs with values:
      | id                                   | title     |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black |

    When I request the api service to create the songs
    Then the api must show me any of the songs if I request them by their id

  Scenario: Songs can be created and retrieved all together
    Given I want to create songs with values:
      | id                                   | title     |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven |

    When I request the api service to create the songs
    Then the api must show me all the songs if I request them

  Scenario: Songs can be created and retrieved all together paginated
    Given I want to create songs with values:
      | id                                   | title     |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Paint it black |
      | bc0bd9a8-0fe4-49a4-aee0-9f0114cd3163 | Wish you were here |
      | 3f225b9b-a114-4b47-b1ae-64a4ac2888cb | Highway to Hell |
      | 45bf5e28-da2f-4207-bf67-466baa7af86e | Stairway to Heaven |

    When I request the api service to create the songs
    Then the api must be able to show me a list with songs from: 0 to: 2
    And the api must be able to show me a list with songs from: 3 to the end

  Scenario: Song without id can not be created
    Given I want to create songs with values:
      | title     |
      | Yesterday |

    When I request the api service to create the songs
    Then the api must return an error response with code: 500
    And the api must not return any song when I request all the stored songs

  Scenario: Song without title can not be created
    Given I want to create songs with values:
      | id                                   |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 |

    When I request the api service to create the songs
    Then the api must return an error response with code: 500
    And the api must not return any song when I request all the stored songs

  Scenario: Song with not unique title can not be created
    Given the following song exists:
      | id                                   | title     |
      | d2efe5df-aaa1-4c06-9e6d-7215860a0a13 | Yesterday |

    And I want to create songs with values:
      | id                                   | title     |
      | 13080dc1-63f2-4770-aa76-683bdf22c5a6 | Yesterday |

    When I request the api service to create the songs
    Then the api must return an error response with code: 409