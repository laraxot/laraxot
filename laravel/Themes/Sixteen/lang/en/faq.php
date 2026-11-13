<?php

declare(strict_types=1);

return [
    'meta' => ['title' => 'Frequently asked questions', 'description' => 'Answers about the FixCity reporting service.', 'breadcrumb' => 'Frequently asked questions'],
    'intro' => 'Practical information about submitting a report, checking updates and protecting your data.',
    'items' => [
        ['question' => 'How do I submit a report?', 'answer' => 'Sign in to your account, open the reporting service and complete the required steps. You can review the summary before submitting.'],
        ['question' => 'Do I need an account to submit a report?', 'answer' => 'Yes. If you are not signed in, the site takes you to the sign-in page before you continue.'],
        ['question' => 'How can I browse reports?', 'answer' => 'You can explore the list and map of reports made public by the municipality. Some details are visible only to the owner or authorised staff.'],
        ['question' => 'How do I track a case?', 'answer' => 'Enter the tracking code you received after submitting on the tracking page. Cases linked to your account are also available in your personal area.'],
        ['question' => 'Where can I find information about data processing?', 'answer' => 'Read the privacy notice published for this service. If the notice is unavailable, the form does not allow a report to be submitted.'],
    ],
    'links' => ['service' => 'Open the reporting service', 'reports' => 'Browse reports', 'tracking' => 'Open tracking', 'privacy' => 'Read the privacy notice'],
    'navigation' => ['label' => 'Related pages', 'home' => 'Back to home'],
    'search' => ['label' => 'Search questions', 'placeholder' => 'Search', 'submit' => 'Search', 'empty' => 'No questions match your search.', 'results' => 'Questions found: :count', 'load_more' => 'Load more questions'],
    'feedback' => ['title' => 'How clear is the information on this page?', 'rating_label' => 'Rate the clarity of this page from one to five stars', 'rate' => 'Rate', 'out_of' => 'stars out of 5', 'next' => 'Next', 'back' => 'Back', 'positive_title' => 'Which aspects did you find most useful?', 'negative_title' => 'Where did you experience the most difficulty?', 'positive_options' => ['The instructions were clear', 'The instructions were complete', 'I always knew what to do next', 'Other'], 'negative_options' => ['The instructions were unclear', 'The instructions were incomplete', 'I did not always know what to do next', 'Other'], 'details_title' => 'Would you like to add more details?', 'details_label' => 'Details', 'thanks' => 'Thank you. Your feedback will help us improve the service.'],
    'contacts' => ['aria_label' => 'Contact options', 'title' => 'Contact the municipality', 'city_title' => 'Problems in the city', 'report' => 'Submit a report', 'track' => 'Track a report', 'login' => 'Sign in to your personal area', 'city_report' => 'Report a problem'],
];
