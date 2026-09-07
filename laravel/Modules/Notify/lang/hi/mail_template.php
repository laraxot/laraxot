<?php

declare(strict_types=1);

return [
    'navigation' => [
        'group' => [
            'name' => 'सूचनाएं',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => 'ईमेल अधिसूचनाओं और उनके टेम्पलेट्स का प्रबंधन'],
=======
            'description' => 'ईमेल अधिसूचनाओं और उनके टेम्पलेट्स का प्रबंधन',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => 'ईमेल अधिसूचनाओं और उनके टेम्पलेट्स का प्रबंधन'],
>>>>>>> a988596b (first)
        'label' => 'ईमेल टेम्पलेट्स',
        'plural' => 'ईमेल टेम्पलेट्स',
        'singular' => 'ईमेल टेम्पलेट',
        'icon' => 'heroicon-o-envelope',
        'sort' => '1',
<<<<<<< HEAD
<<<<<<< HEAD
        'name' => 'ईमेल टेम्पलेट'],
=======
        'name' => 'ईमेल टेम्पलेट',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'name' => 'ईमेल टेम्पलेट'],
>>>>>>> a988596b (first)
    'fields' => [
        'id' => [
            'label' => 'आईडी',
            'helper_text' => 'टेम्पलेट की विशिष्ट पहचान',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'mailable' => [
            'label' => 'मेलेबल क्लास',
            'placeholder' => 'मेलेबल क्लास का नाम दर्ज करें',
            'help' => 'ईमेल भेजने वाला PHP क्लास',
            'helper_text' => 'ईमेल भेजने का PHP क्लास',
            'description' => 'मेलेबल',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'subject' => [
            'label' => 'विषय',
            'placeholder' => 'ईमेल विषय दर्ज करें',
            'help' => 'ईमेल में दिखाई देने वाला विषय',
            'helper_text' => 'ईमेल विषय',
            'description' => 'विषय',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'html_template' => [
            'label' => 'HTML सामग्री',
            'placeholder' => 'ईमेल HTML सामग्री दर्ज करें',
            'help' => 'HTML प्रारूप में ईमेल सामग्री',
            'helper_text' => 'ईमेल टेम्पलेट की HTML सामग्री',
            'description' => 'HTML टेम्पलेट',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'text_template' => [
            'label' => 'पाठ सामग्री',
            'placeholder' => 'ईमेल पाठ सामग्री दर्ज करें',
            'help' => 'HTML का समर्थन नहीं करने वाले क्लाइंट्स के लिए पाठ संस्करण',
            'helper_text' => 'ईमेल टेम्पलेट का पाठ संस्करण',
            'description' => 'पाठ टेम्पलेट',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'version' => [
            'label' => 'संस्करण',
            'help' => 'टेम्पलेट संस्करण संख्या',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'created_at' => [
            'label' => 'बनाया गया',
            'helper_text' => 'टेम्पलेट बनाने की तारीख',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'updated_at' => [
            'label' => 'अंतिम संशोधन',
            'helper_text' => 'टेम्पलेट अंतिम संशोधन की तारीख',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'from_email' => [
            'label' => 'प्रेषक ईमेल',
            'helper_text' => 'प्रेषक का ईमेल पता',
            'placeholder' => 'noreply@example.com',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'from_name' => [
            'label' => 'प्रेषक का नाम',
            'helper_text' => 'प्रदर्शित प्रेषक का नाम',
            'placeholder' => 'कंपनी का नाम',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'variables' => [
            'label' => 'उपलब्ध चर',
            'helper_text' => 'टेम्पलेट में उपयोग किए जा सकने वाले चरों की सूची',
            'placeholder' => 'उदा: {{name}}, {{email}}',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'is_markdown' => [
            'label' => 'मार्कडाउन उपयोग करें',
            'helper_text' => 'बताता है कि क्या टेम्पलेट मार्कडाउन वाक्य रचना का उपयोग करता है',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'status' => [
            'label' => 'स्थिति',
            'helper_text' => 'टेम्पलेट की वर्तमान स्थिति',
            'tooltip' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'toggleColumns' => [
            'label' => 'कॉलम टॉगल करें',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'reorderRecords' => [
            'label' => 'रिकॉर्ड पुनः क्रमित करें',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'resetFilters' => [
            'label' => 'फ़िल्टर रीसेट करें',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'applyFilters' => [
            'label' => 'फ़िल्टर लागू करें',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'openFilters' => [
            'label' => 'फ़िल्टर खोलें',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'layout' => [
            'label' => 'लेआउट',
            'tooltip' => '',
            'helper_text' => '',
<<<<<<< HEAD
<<<<<<< HEAD
            'description' => ''],
=======
            'description' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'description' => ''],
>>>>>>> a988596b (first)
        'slug' => [
            'label' => 'स्लग',
            'description' => 'स्लग',
            'helper_text' => 'स्लग',
            'placeholder' => 'स्लग',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'name' => [
            'description' => 'टेम्पलेट का नाम',
            'helper_text' => 'टेम्पलेट को पहचानने के लिए वर्णनात्मक नाम',
            'placeholder' => 'उदा: स्वागत, आदेश पुष्टि, पासवर्ड रीसेट',
            'label' => 'टेम्पलेट का नाम',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => ''],
=======
            'tooltip' => '',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => ''],
>>>>>>> a988596b (first)
        'params' => [
            'label' => 'पैरामीटर्स',
            'helper_text' => 'टेम्पलेट में उपयोग किए जा सकने वाले पैरामीटर अल्पविराम से अलग करके दर्ज करें',
            'placeholder' => 'name, email, date, company',
            'description' => 'ईमेल टेम्पलेट के लिए उपलब्ध पैरामीटर्स',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => '']],
=======
            'tooltip' => '',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tooltip' => '']],
>>>>>>> a988596b (first)
    'filters' => [
        'search_placeholder' => 'टेम्पलेट्स खोजें...',
        'version' => [
            'label' => 'संस्करण',
<<<<<<< HEAD
<<<<<<< HEAD
            'placeholder' => 'संस्करण चुनें']],
=======
            'placeholder' => 'संस्करण चुनें',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'placeholder' => 'संस्करण चुनें']],
>>>>>>> a988596b (first)
    'actions' => [
        'create' => [
            'label' => 'नया टेम्पलेट',
            'modal' => [
                'heading' => 'ईमेल टेम्पलेट बनाएं',
                'description' => 'नए ईमेल टेम्पलेट के लिए विवरण दर्ज करें',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'बनाएं']],
=======
                'submit' => 'बनाएं',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'बनाएं']],
>>>>>>> a988596b (first)
        'edit' => [
            'label' => 'संपादित करें',
            'modal' => [
                'heading' => 'ईमेल टेम्पलेट संपादित करें',
                'description' => 'ईमेल टेम्पलेट विवरण संशोधित करें',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'सहेजें']],
=======
                'submit' => 'सहेजें',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'सहेजें']],
>>>>>>> a988596b (first)
        'delete' => [
            'label' => 'हटाएं',
            'modal' => [
                'heading' => 'ईमेल टेम्पलेट हटाएं',
                'description' => 'क्या आप वाकई इस टेम्पलेट को हटाना चाहते हैं? यह क्रिया पूर्ववत नहीं की जा सकती।',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'हटाएं']],
        'restore' => [
            'label' => 'पुनर्स्थापित करें'],
=======
                'submit' => 'हटाएं',
            ],
        ],
        'restore' => [
            'label' => 'पुनर्स्थापित करें',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'हटाएं']],
        'restore' => [
            'label' => 'पुनर्स्थापित करें'],
>>>>>>> a988596b (first)
        'force_delete' => [
            'label' => 'स्थायी रूप से हटाएं',
            'modal' => [
                'heading' => 'ईमेल टेम्पलेट स्थायी रूप से हटाएं',
                'description' => 'क्या आप वाकई इस टेम्पलेट को स्थायी रूप से हटाना चाहते हैं? यह क्रिया पूर्ववत नहीं की जा सकती।',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'स्थायी रूप से हटाएं']],
=======
                'submit' => 'स्थायी रूप से हटाएं',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'स्थायी रूप से हटाएं']],
>>>>>>> a988596b (first)
        'new_version' => [
            'label' => 'नया संस्करण',
            'modal' => [
                'heading' => 'नया संस्करण बनाएं',
                'description' => 'ईमेल टेम्पलेट का नया संस्करण बनाएं',
<<<<<<< HEAD
<<<<<<< HEAD
                'submit' => 'संस्करण बनाएं']],
=======
                'submit' => 'संस्करण बनाएं',
            ],
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'submit' => 'संस्करण बनाएं']],
>>>>>>> a988596b (first)
        'preview' => [
            'label' => 'पूर्वावलोकन',
            'tooltip' => 'ईमेल पूर्वावलोकन देखें',
            'success_message' => 'पूर्वावलोकन सफलतापूर्वक बनाया गया',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'पूर्वावलोकन बनाने में त्रुटि'],
=======
            'error_message' => 'पूर्वावलोकन बनाने में त्रुटि',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'पूर्वावलोकन बनाने में त्रुटि'],
>>>>>>> a988596b (first)
        'test' => [
            'label' => 'परीक्षण भेजें',
            'tooltip' => 'परीक्षण ईमेल भेजें',
            'success_message' => 'परीक्षण ईमेल सफलतापूर्वक भेजा गया',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'परीक्षण ईमेल भेजने में त्रुटि'],
=======
            'error_message' => 'परीक्षण ईमेल भेजने में त्रुटि',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'परीक्षण ईमेल भेजने में त्रुटि'],
>>>>>>> a988596b (first)
        'duplicate' => [
            'label' => 'प्रतिलिपि बनाएं',
            'tooltip' => 'टेम्पलेट की प्रतिलिपि बनाएं',
            'success_message' => 'टेम्पलेट की प्रतिलिपि सफलतापूर्वक बनाई गई',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'टेम्पलेट की प्रतिलिपि बनाने में त्रुटि'],
=======
            'error_message' => 'टेम्पलेट की प्रतिलिपि बनाने में त्रुटि',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'टेम्पलेट की प्रतिलिपि बनाने में त्रुटि'],
>>>>>>> a988596b (first)
        'export' => [
            'label' => 'निर्यात करें',
            'tooltip' => 'JSON प्रारूप में टेम्पलेट निर्यात करें',
            'success_message' => 'टेम्पलेट सफलतापूर्वक निर्यात किया गया',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'टेम्पलेट निर्यात करने में त्रुटि'],
=======
            'error_message' => 'टेम्पलेट निर्यात करने में त्रुटि',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'टेम्पलेट निर्यात करने में त्रुटि'],
>>>>>>> a988596b (first)
        'import' => [
            'label' => 'आयात करें',
            'tooltip' => 'JSON फ़ाइल से टेम्पलेट आयात करें',
            'success_message' => 'टेम्पलेट सफलतापूर्वक आयात किया गया',
<<<<<<< HEAD
<<<<<<< HEAD
            'error_message' => 'टेम्पलेट आयात करने में त्रुटि']],
=======
            'error_message' => 'टेम्पलेट आयात करने में त्रुटि',
        ],
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error_message' => 'टेम्पलेट आयात करने में त्रुटि']],
>>>>>>> a988596b (first)
    'messages' => [
        'created' => 'ईमेल टेम्पलेट सफलतापूर्वक बनाया गया।',
        'updated' => 'ईमेल टेम्पलेट सफलतापूर्वक अपडेट किया गया।',
        'deleted' => 'ईमेल टेम्पलेट सफलतापूर्वक हटाया गया।',
        'restored' => 'ईमेल टेम्पलेट सफलतापूर्वक पुनर्स्थापित किया गया।',
        'force_deleted' => 'ईमेल टेम्पलेट स्थायी रूप से हटाया गया।',
        'version_created' => 'नया टेम्पलेट संस्करण सफलतापूर्वक बनाया गया।',
        'success' => 'ऑपरेशन सफलतापूर्वक पूरा हुआ',
        'error' => 'ऑपरेशन के दौरान त्रुटि हुई',
        'confirmation' => 'क्या आप वाकई इस ऑपरेशन को जारी रखना चाहते हैं?',
        'template_created' => 'ईमेल टेम्पलेट सफलतापूर्वक बनाया गया है',
        'template_updated' => 'ईमेल टेम्पलेट सफलतापूर्वक अपडेट किया गया है',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'template_deleted' => 'ईमेल टेम्पलेट सफलतापूर्वक हटाया गया है'],
    'sections' => [
        'template' => [
            'label' => 'टेम्पलेट',
            'description' => 'टेम्पलेट की मुख्य जानकारी'],
        'versions' => [
            'label' => 'संस्करण',
            'description' => 'टेम्पलेट संस्करण इतिहास'],
        'logs' => [
            'label' => 'लॉग्स',
            'description' => 'टेम्पलेट भेजने का इतिहास'],
<<<<<<< HEAD
=======
        'template_deleted' => 'ईमेल टेम्पलेट सफलतापूर्वक हटाया गया है',
    ],
    'sections' => [
        'template' => [
            'label' => 'टेम्पलेट',
            'description' => 'टेम्पलेट की मुख्य जानकारी',
        ],
        'versions' => [
            'label' => 'संस्करण',
            'description' => 'टेम्पलेट संस्करण इतिहास',
        ],
        'logs' => [
            'label' => 'लॉग्स',
            'description' => 'टेम्पलेट भेजने का इतिहास',
        ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        'main' => 'मुख्य जानकारी',
        'content' => 'सामग्री',
        'styling' => 'स्टाइलिंग',
        'settings' => 'सेटिंग्स',
<<<<<<< HEAD
<<<<<<< HEAD
        'variables' => 'चर'],
=======
        'variables' => 'चर',
    ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'variables' => 'चर'],
>>>>>>> a988596b (first)
    'status' => [
        'sent' => 'भेजा गया',
        'delivered' => 'पहुंचा दिया गया',
        'failed' => 'विफल',
        'opened' => 'खोला गया',
        'clicked' => 'क्लिक किया गया',
        'bounced' => 'वापस आया',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'spam' => 'स्पैम के रूप में चिह्नित'],
    'model' => [
        'label' => 'ईमेल टेम्पलेट'],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label'];
<<<<<<< HEAD
=======
        'spam' => 'स्पैम के रूप में चिह्नित',
    ],
    'model' => [
        'label' => 'ईमेल टेम्पलेट',
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
