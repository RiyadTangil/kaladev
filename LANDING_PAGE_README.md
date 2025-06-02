# FoodKing Landing Page

This document provides information about the new landing page implemented for the FoodKing food ordering application.

## Overview

The landing page is a modern, professional, and eye-catching introduction to the FoodKing application. It showcases the key features and benefits of using the service, with sections for:

- Hero banner with call-to-action buttons
- How It Works explanation
- Popular Categories display
- Featured Items showcase
- Customer Testimonials
- Mobile App download section
- Newsletter subscription
- Contact information and form

## Accessing the Landing Page

The landing page is accessible at the following URL:
```
http://localhost:8080/landing
```

## Route Configuration

The landing page is registered as a frontend route in `resources/js/router/modules/frontendRoutes.js` with the name `frontend.landing`.

## Components

The landing page is implemented as a Vue component in:
```
resources/js/components/frontend/landing/LandingPage.vue
```

## Customization

### Images

The landing page requires the following images to be placed in the `public/images/` directory:

- `hero-food.png` - The main hero image showing delicious food
- `google-play.png` - Google Play Store badge
- `app-store.png` - Apple App Store badge
- `mobile-app.png` - Mobile app screenshot or mockup

You can replace these with your own images while maintaining the same filenames, or update the image paths in the component.

### Content

To customize the content of the landing page, edit the `LandingPage.vue` file. The component uses data from the following Vuex stores:

- `frontendSetting` - For app links and general settings
- `frontendCompany` - For company information like address, phone, and email
- `frontendItemCategory` - For displaying popular categories
- `frontendItem` - For displaying featured items

### Colors and Styling

The landing page uses the same color scheme as the main application, with the primary color being a gradient from `#FF016C` to `#FF7A00`. If you want to change the colors, update the gradient values in the component.

## Making the Landing Page the Default Home Page

To make the landing page the default entry point for your application, modify the routes configuration to redirect the root path to the landing page:

1. Open `resources/js/router/modules/frontendRoutes.js`
2. Add a new route at the beginning of the routes array:

```javascript
{
    path: '/',
    redirect: '/landing'
},
```

## Integration with Existing Features

The landing page integrates with several existing features of the application:

- Menu navigation
- User authentication (Sign Up/Login)
- Newsletter subscription
- Mobile app download links

These integrations use the existing API endpoints and Vuex store modules.

## SEO Considerations

For better SEO, consider adding appropriate meta tags to the landing page. You can do this by updating the `resources/views/master.blade.php` file with dynamic meta tags based on the current route.

## Performance

The landing page is designed to be lightweight and fast-loading. It uses lazy loading for images and minimizes unnecessary API calls.

## Feedback and Support

If you have any questions or need assistance with the landing page, please contact the development team. 