Feature: Home Page

  Ensure the home page content is available

  Scenario: View the homepage content
    Given I am on the homepage
    Then the response status code should be 200
    # Test some menu items.
    And I should see "Publications"
    And I should see "News & Media"
    And I should see "Contact"
    # Test block presence in panel.
    And I should see "About Us"
    And I should see "Latest News"
    And I should see "View More News"
    And I should see "From the Blog"
#    And I should see "View more blog articles"
    # Test block presence in sidebar.
    And I should see "Twitter Feed"
    And I should see "Quick Links"
    And I should see "Connect with us"
