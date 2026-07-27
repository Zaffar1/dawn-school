<?php

return [
    'site_name' => 'KiOhana',
    'title_suffix' => ' | KiOhana',
    'default_title' => 'KiOhana Funeral Planning, Cemetery Plots, and Memorial Services',
    'default_description' => 'Plan funeral services, compare funeral homes, arrange cemetery plots, create memorials, and manage funeral-related needs online with KiOhana.',
    'default_image' => 'resources/web/images/logo.png',
    'default_robots' => 'index,follow',
    'noindex_route_names' => [
        'cart',
        'checkout',
        'dashboard',
        'profile.view',
        'show.2fa',
        'show.forget-password',
        'web.reset-password.get',
        'invoice.public.sign.customer',
        'invoice.public.sign.csc',
        'cemetery.invoice.public.sign.customer',
        'cemetery.invoice.public.sign.csc',
        'gravestone.invoice.public.sign.customer',
        'gravestone.invoice.public.sign.csc',
    ],
    'preserve_query_route_names' => [
        'location',
        'funeral-home',
        'all-products',
        'all-services',
        'all-packages',
        'cemetery.location',
        'gravestone.location',
    ],
    'route_defaults' => [
        'index' => [
            'title' => 'KiOhana Funeral Planning and Cremation Services',
            'description' => 'Plan funeral services online, compare funeral homes, explore cremation and cemetery options, and understand what to do when someone dies with KiOhana.',
            'keywords' => [
                'affordable cremation near me',
                'best funeral homes',
                'best funeral plans',
                'cremation',
                'cremation near me',
                'cremation services',
                'cremation services near me',
                'direct cremation near me',
                'direct cremation services near me',
                'funeral',
                'funeral arrangements',
                'funeral home assistance',
                'funeral home near me',
                'funeral home services',
                'funeral homes',
                'funeral homes near me',
                'funeral plan',
                'funeral planning',
                'funeral plans',
                'funeral plans for seniors',
                'funeral preplanning',
                'funeral service',
                'funeral services',
                'how to plan a funeral',
                'local funeral home',
                'mortuary affairs',
                'mortuary near me',
                'online funeral services',
                'plan a funeral',
                'what to do when someone dies',
            ],
        ],
        'about' => [
            'title' => 'About KiOhana Funeral Planning',
            'description' => 'Learn how KiOhana helps families plan funeral services, memorials, cemetery needs, and related arrangements online.',
            'keywords' => [
                'online funeral services',
                'funeral planning',
            ],
        ],
        'contact' => [
            'title' => 'Contact KiOhana Funeral Planning Support',
            'description' => 'Contact KiOhana for help with funeral planning, memorial services, cemetery plots, and account support.',
            'keywords' => [
                'local funeral home',
                'funeral help',
            ],
        ],
        'faqs' => [
            'title' => 'Funeral Planning FAQs',
            'description' => 'Find answers about immediate funeral care, cremation, hospice funeral preplanning, life insurance, cemetery plots, and headstones and markers with KiOhana.',
            'keywords' => [
                'funeral help',
                'how to plan a funeral',
                'what to do when someone dies',
                'cremation services',
                'funeral planning',
                'cover funeral costs',
            ],
        ],
        'blog' => [
            'title' => 'Funeral Planning Blog',
            'description' => 'Read KiOhana articles about funeral planning, cremation, hospice preplanning, life insurance, cemetery plots, funding, and memorials.',
            'keywords' => [
                'funeral planning',
                'funeral help',
                'cremation services',
                'cover funeral costs',
                'funeral preplanning',
                'what to do when someone dies',
            ],
        ],
        'veterans' => [
            'title' => 'Veteran Funeral and Memorial Support',
            'description' => 'Explore KiOhana resources for veteran funeral planning, memorial services, and family support.',
        ],
        'location' => [
            'title' => 'Find Funeral Homes Near You',
            'description' => 'Search funeral homes by location and start planning funeral services online with KiOhana.',
            'keywords' => [
                'best funeral homes',
                'funeral home near me',
                'funeral homes near me',
                'local funeral home',
                'mortuary near me',
            ],
        ],
        'funeral-home' => [
            'title' => 'Compare Funeral Homes Online',
            'description' => 'Compare funeral homes, locations, and planning options available through KiOhana before starting funeral arrangements.',
            'keywords' => [
                'funeral home assistance',
                'funeral home services',
                'funeral homes',
                'mortuary affairs',
            ],
        ],
        'all-products' => [
            'title' => 'Funeral Products and Memorial Items',
            'description' => 'Browse funeral products, memorial items, and related planning selections available through KiOhana.',
        ],
        'all-services' => [
            'title' => 'Cremation Services and Funeral Services',
            'description' => 'Browse cremation services, funeral service options, and support for immediate or same day cremation needs through KiOhana.',
            'keywords' => [
                'affordable cremation near me',
                'cremation',
                'cremation cost',
                'cremation near me',
                'cremation services',
                'cremation services near me',
                'direct cremation near me',
                'direct cremation services near me',
                'funeral service',
                'immediate cremation',
                'same day cremation',
            ],
        ],
        'all-packages' => [
            'title' => 'Funeral Planning Packages',
            'description' => 'Compare funeral packages and service bundles available through KiOhana.',
            'keywords' => [
                'best funeral plans',
                'funeral arrangements',
                'funeral plans',
            ],
        ],
        'obituary' => [
            'title' => 'Search Obituaries and Memorial Pages',
            'description' => 'Search obituaries and memorial pages published through KiOhana while planning next steps for a loved one.',
            'keywords' => [
                'what to do when someone dies',
                'funeral planning',
            ],
        ],
        'obituary.detail' => [
            'title' => 'Obituary Memorial Details',
            'description' => 'View obituary and memorial details while planning next steps with KiOhana.',
            'keywords' => [
                'what to do when someone dies',
                'funeral planning',
            ],
        ],
        'obituary.packages' => [
            'title' => 'Obituary and Memorial Packages',
            'description' => 'Review obituary and memorial package options for honoring a loved one through KiOhana.',
            'keywords' => [
                'funeral arrangements',
                'funeral services',
            ],
        ],
        'cemetery.location' => [
            'title' => 'Find Cemetery Plots by Location',
            'description' => 'Search cemetery plot options by location and continue burial planning through KiOhana.',
            'keywords' => [
                'burial',
                'burial services near me',
                'cemetery',
                'cemetery near me',
            ],
        ],
        'gravestone.location' => [
            'title' => 'Find Headstones and Markers Providers',
            'description' => 'Search headstones and markers providers and memorial options connected to cemetery and burial planning needs.',
            'keywords' => [
                'burial',
                'burial services near me',
                'cemetery',
                'cemetery near me',
            ],
        ],
        'gravestones.index' => [
            'title' => 'Headstones and Markers',
            'description' => 'Browse headstones, markers, and memorial product options through KiOhana.',
        ],
        'funeral-funding' => [
            'title' => 'Funeral Funding and Insurance Help',
            'description' => 'Get help with burial insurance policy questions, funeral insurance plans, cremation cost planning, and ways to cover funeral costs.',
            'keywords' => [
                'burial insurance policy',
                'cover funeral costs',
                'cremation cost',
                'funeral cost calculator',
                'funeral cover insurance',
                'funeral insurance help',
                'funeral insurance plan',
            ],
        ],
        'fund-raise' => [
            'title' => 'Funeral Fundraisers for Families',
            'description' => 'Create or explore funeral fundraisers that help families cover funeral and memorial expenses.',
            'keywords' => [
                'funeral help',
                'cover funeral costs',
            ],
        ],
        'fund-raise.details' => [
            'title' => 'Funeral Fundraiser Details',
            'description' => 'Support a funeral fundraiser and help families cover funeral and memorial expenses.',
            'keywords' => [
                'funeral help',
                'cover funeral costs',
            ],
        ],
        'fundraising' => [
            'title' => 'Start a Funeral Fundraiser',
            'description' => 'Start a funeral fundraiser to help family and friends cover funeral costs through KiOhana.',
            'keywords' => [
                'funeral help',
                'cover funeral costs',
            ],
        ],
        'fundme.funeral' => [
            'title' => 'Farewell Funeral Funding and Urgent Support',
            'description' => 'Find funeral help, start Farewell Funeral Funding support for urgent funeral needs, and explore options when families need funeral services today.',
            'keywords' => [
                'emergency funeral services',
                'funeral help',
                'funeral services today',
                'immediate cremation',
                'same day cremation',
            ],
        ],
        'preplan.index' => [
            'title' => 'Funeral Preplanning and Funeral Plans',
            'description' => 'Review funeral preplanning, funeral plans for seniors, and steps for how to plan a funeral through KiOhana.',
            'keywords' => [
                'funeral plan',
                'funeral plans for seniors',
                'funeral preplanning',
                'how to plan a funeral',
                'plan a funeral',
            ],
        ],
        'hospice.register' => [
            'title' => 'Hospice Provider Registration',
            'description' => 'Hospice providers can register with KiOhana to connect families with funeral planning, memorial, and support resources.',
            'keywords' => [
                'hospice',
            ],
        ],
        'hospice.success' => [
            'title' => 'Hospice Registration Submitted',
            'description' => 'Confirm a hospice provider registration was submitted to KiOhana for review and next-step support.',
        ],
        'privacy-policy' => [
            'title' => 'KiOhana Privacy Policy and Data Practices',
            'description' => 'Read the KiOhana privacy policy for details about how personal information is collected, used, protected, and shared.',
        ],
        'terms-conditions' => [
            'title' => 'KiOhana Terms for Funeral Planning Services',
            'description' => 'Review KiOhana terms and conditions for using funeral planning, cemetery, memorial, and related online services.',
        ],
        'funeral-home-optionTwo' => [
            'title' => 'Start Funeral Planning by Location',
            'description' => 'Begin funeral planning by choosing a location, comparing providers, and finding services available through KiOhana.',
            'keywords' => [
                'funeral planning',
                'funeral homes near me',
                'local funeral home',
            ],
        ],
        'funeral-home-optionTwo.deceased' => [
            'title' => 'Deceased Information for Funeral Planning',
            'description' => 'Provide required deceased information so KiOhana can help organize funeral service, cremation, cemetery, and memorial needs.',
            'keywords' => [
                'funeral arrangements',
                'funeral planning',
                'what to do when someone dies',
            ],
        ],
        'view.funeral-home' => [
            'title' => 'Funeral Home Details',
            'description' => 'View funeral home details, service options, location information, and next steps for planning with KiOhana.',
            'keywords' => [
                'best funeral homes',
                'funeral home services',
                'funeral homes',
            ],
        ],
        'cart.shared' => [
            'title' => 'Shared Funeral Planning Cart',
            'description' => 'Review a shared KiOhana planning cart with selected funeral, cremation, cemetery, or memorial items.',
        ],
        'viewing-rooms' => [
            'title' => 'Funeral Viewing Rooms',
            'description' => 'Browse funeral viewing room options that can support family gatherings, memorial services, and visitation needs.',
            'keywords' => [
                'funeral arrangements',
                'funeral service',
                'funeral services',
            ],
        ],
        'show-room' => [
            'title' => 'Viewing Room Details',
            'description' => 'Review viewing room details, availability, and options for funeral or memorial service arrangements.',
            'keywords' => [
                'funeral arrangements',
                'funeral service',
            ],
        ],
        'view-product' => [
            'title' => 'Funeral Product Details',
            'description' => 'Review funeral product details, pricing, and options while planning services and memorial arrangements with KiOhana.',
        ],
        'view-package' => [
            'title' => 'Funeral Package Details',
            'description' => 'Review funeral package details, included services, products, pricing, and next steps for online funeral planning.',
            'keywords' => [
                'best funeral plans',
                'funeral plans',
                'funeral arrangements',
            ],
        ],
        'view-service' => [
            'title' => 'Funeral Service Details',
            'description' => 'Review funeral service details, add-ons, pricing, and planning options for cremation, memorial, and burial needs.',
            'keywords' => [
                'cremation services',
                'funeral service',
                'funeral services',
            ],
        ],
        'transport.view' => [
            'title' => 'Funeral Transportation Planning',
            'description' => 'Add transportation details to a KiOhana funeral planning order and coordinate service logistics.',
        ],
        'video-tribute' => [
            'title' => 'Memorial Video Tribute',
            'description' => 'Create a memorial video tribute with photos, music, and memories as part of a KiOhana funeral planning order.',
        ],
        'obituary.create' => [
            'title' => 'Create an Obituary Memorial Page',
            'description' => 'Create an obituary and memorial details through KiOhana as part of funeral or memorial service planning.',
            'keywords' => [
                'what to do when someone dies',
                'online funeral services',
            ],
        ],
        'obituaries.showOb' => [
            'title' => 'Obituary Memorial Page',
            'description' => 'View an obituary memorial page with details about a loved one and related KiOhana memorial support.',
            'keywords' => [
                'what to do when someone dies',
                'online funeral services',
            ],
        ],
        'obituary.success' => [
            'title' => 'Obituary Submission Received',
            'description' => 'Confirm an obituary submission and review next steps for memorial publishing through KiOhana.',
        ],
        'obituary.data' => [
            'title' => 'Obituary Information Form',
            'description' => 'Add obituary information, memorial details, and tribute content for a KiOhana obituary page.',
        ],
        'cemetery.vendors' => [
            'title' => 'Cemetery Providers Near You',
            'description' => 'Compare cemetery providers by location and review cemetery plot options through KiOhana.',
            'keywords' => [
                'burial services near me',
                'cemetery',
                'cemetery near me',
            ],
        ],
        'cemetery.all_products' => [
            'title' => 'Cemetery Plot Products',
            'description' => 'Browse cemetery plot products, burial options, and related cemetery planning selections through KiOhana.',
            'keywords' => [
                'burial',
                'cemetery',
                'cemetery near me',
            ],
        ],
        'vendor.detail' => [
            'title' => 'Cemetery Provider Details',
            'description' => 'Review cemetery provider details, available burial plot options, location information, and next planning steps.',
            'keywords' => [
                'burial services near me',
                'cemetery',
                'cemetery near me',
            ],
        ],
        'cemetery-registration.show' => [
            'title' => 'Cemetery Provider Registration',
            'description' => 'Cemetery providers can register with KiOhana to offer cemetery plots, burial support, and related services to families.',
            'keywords' => [
                'burial',
                'cemetery',
            ],
        ],
        'cemetery-login.show' => [
            'title' => 'Cemetery Provider Login',
            'description' => 'Cemetery providers can access their KiOhana account to manage cemetery plot services and related information.',
        ],
        'gravestone.register' => [
            'title' => 'Headstones and Markers Provider Registration',
            'description' => 'Headstones and markers providers can register with KiOhana to offer memorial products to families.',
            'keywords' => [
                'burial',
                'cemetery',
            ],
        ],
        'gravestone.login' => [
            'title' => 'Headstones and Markers Provider Login',
            'description' => 'Headstones and markers providers can access their KiOhana account to manage memorial product information.',
        ],
        'gravestones.show' => [
            'title' => 'Headstone and Marker Details',
            'description' => 'Review headstone and marker details, memorial options, and related cemetery planning information.',
            'keywords' => [
                'burial',
                'cemetery',
            ],
        ],
        'grave.stone.makers' => [
            'title' => 'Headstones and Markers Providers',
            'description' => 'Find headstones and markers providers, compare memorial product options, and continue cemetery planning through KiOhana.',
            'keywords' => [
                'burial',
                'cemetery',
                'cemetery near me',
            ],
        ],
        'grave.stone.products' => [
            'title' => 'Headstones and Markers Products',
            'description' => 'Browse headstones, markers, and memorial products from a provider connected through KiOhana.',
            'keywords' => [
                'burial',
                'cemetery',
            ],
        ],
        'grave.stone.maker.product' => [
            'title' => 'Memorial Product Details',
            'description' => 'Review memorial product details, design options, and next steps for headstones and markers through KiOhana.',
            'keywords' => [
                'burial',
                'cemetery',
            ],
        ],
        'grave.stone.maker.cart.show' => [
            'title' => 'Headstones and Markers Cart',
            'description' => 'Review selected headstones and markers products before continuing with memorial planning through KiOhana.',
        ],
        'invoice.public.sign.customer' => [
            'title' => 'Customer Funeral Invoice Signing',
            'description' => 'Secure customer invoice signing page for authorized KiOhana funeral planning and service arrangements.',
        ],
        'invoice.public.sign.coordinator' => [
            'title' => 'Coordinator Funeral Invoice Signing',
            'description' => 'Secure coordinator invoice signing page for authorized KiOhana funeral planning and service arrangements.',
        ],
        'cemetery.invoice.public.sign.customer' => [
            'title' => 'Customer Cemetery Invoice Signing',
            'description' => 'Secure customer invoice signing page for authorized KiOhana cemetery plot and burial arrangements.',
        ],
        'cemetery.invoice.public.sign.coordinator' => [
            'title' => 'Coordinator Cemetery Invoice Signing',
            'description' => 'Secure coordinator invoice signing page for authorized KiOhana cemetery plot and burial arrangements.',
        ],
        'gravestone.invoice.public.sign.customer' => [
            'title' => 'Customer Headstone Invoice Signing',
            'description' => 'Secure customer invoice signing page for authorized KiOhana headstones and markers arrangements.',
        ],
        'gravestone.invoice.public.sign.coordinator' => [
            'title' => 'Coordinator Headstone Invoice Signing',
            'description' => 'Secure coordinator invoice signing page for authorized KiOhana headstones and markers arrangements.',
        ],
        'policy.show' => [
            'title' => 'Express Funeral Funding Details',
            'description' => 'Review Express Funeral Funding details, life insurance policy information, and payment support for funeral costs.',
            'keywords' => [
                'burial insurance policy',
                'funeral cover insurance',
                'funeral insurance help',
                'funeral insurance plan',
            ],
        ],
        'advertisement.create' => [
            'title' => 'Advertise With KiOhana Service Listings',
            'description' => 'Create an advertisement placement with KiOhana to reach families planning funeral, cemetery, and memorial services.',
        ],
        'register' => [
            'title' => 'Business Registration',
            'description' => 'Funeral homes and related service providers can register a business account with KiOhana.',
        ],
        'user.register' => [
            'title' => 'KiOhana User Registration for Planning',
            'description' => 'Create a KiOhana user account to save funeral planning, memorial, cemetery, and funding details.',
        ],
        'user.login' => [
            'title' => 'KiOhana Account Login for Planning',
            'description' => 'Sign in to a KiOhana user account to continue funeral planning, memorial, cemetery, or funding tasks.',
        ],
        'thank-you' => [
            'title' => 'KiOhana Funeral Planning Order Thank You',
            'description' => 'Confirm a KiOhana order submission and review next steps for funeral planning support.',
        ],
        'register-success' => [
            'title' => 'Business Registration Received',
            'description' => 'Confirm a KiOhana business registration was received and review next steps for provider account access.',
        ],
    ],
    'pagespeed_urls' => [
        '/',
        '/about-us',
        '/contact-us',
        '/faqs',
        '/blog',
        '/veterans',
        '/location',
        '/obituary',
        '/fund-me-funerals',
    ],
];
