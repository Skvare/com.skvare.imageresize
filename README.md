# com.skvare.imageresize

## Overview

The Image Resize extension adds powerful image resizing capabilities to CiviCRM, allowing administrators to display contact photos and custom field images at different dimensions without compromising page load performance. This extension automatically resizes images on-demand and caches them for optimal performance, making it ideal for websites that display many contact images or need consistent image dimensions across their CiviCRM integration.

**Key Features:**
- Dynamic image resizing for contact photos and custom field images
- Multiple predefined image styles (thumbnail, medium, large, etc.)
- Custom image style creation and management
- Automatic caching for improved performance
- URL-based image resizing parameters
- Integration with Drupal Views, WordPress plugins, and other CMS systems
- Support for all common image formats (JPEG, PNG, GIF, WebP)
- Maintains aspect ratios while resizing

## Benefits

- **Improved Performance:** Smaller, optimized images load faster than full-size originals
- **Consistent Design:** Standardized image dimensions across your website
- **Bandwidth Savings:** Reduced data transfer for mobile and low-bandwidth users
- **Flexible Usage:** Multiple image sizes from a single uploaded image
- **Automatic Processing:** No manual image editing required

## Requirements

- **CiviCRM:** 5.38 or higher
- **PHP:** 7.4 or higher (recommended 8.0+)
- **Image Processing:** GD or ImageMagick PHP extension
- **Storage:** Adequate disk space for cached resized images

## Installation (Web UI)

* Move the downloaded extension to your extensions folder.
* Goto civicrm/admin/extensions -- install the extension

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/skvare/com.skvare.imageresize.git
cv en imageresize
```

## Configuration

### Setting Up Image Styles

After installation, configure image styles for your needs:

1. **Navigate to:** `Administer > System Settings > Option Groups > Image Styles`
  - Or directly: `/civicrm/admin/options/image_styles`

2. **Create Custom Image Styles:**
  - Click **Add Image Style**
  - Enter style name (e.g., "profile_thumb", "directory_medium")
  - Set dimensions (width x height in pixels)


Image Style Options

![Screenshot](/images/screenshot.png)


## Usage

### Basic Image Resizing

#### Contact Images

To display a resized contact image, modify the image URL:

**Original Format:**
```
/civicrm/contact/imagefile?photo=contact_123_photo.jpg
```

**Resized Format:**
```
/civicrm/contact/imagefile?photo=contact_123_photo.jpg&image_styles=thumbnail
```

#### Custom Field Images

For custom field images, use similar URL parameters:

```
/civicrm/file?reset=1&filename=custom_image.png&mime-type=image/png&image_styles=medium
```

### Integration Examples

#### Drupal Views Integration

When creating Views that display CiviCRM contacts:

1. Add the contact image field to your View
2. In the field settings, modify the image URL to include `&image_styles=your_style_name`
3. Choose appropriate image style based on your layout needs

```php
// In Drupal template or preprocess function
$resized_url = $original_url . '&image_styles=profile_thumb';
```

#### WordPress Plugin Integration

For WordPress sites displaying CiviCRM contact directories:

```php
// In your WordPress theme or plugin
$contact_image = get_civicrm_contact_image($contact_id, 'medium');
echo '<img src="' . $contact_image . '" alt="Contact Photo" />';
```

#### Custom HTML/CSS

```html
<!-- Staff directory with consistent thumbnail sizes -->
<div class="staff-member">
  <img src="/civicrm/contact/imagefile?photo=staff_photo.jpg&image_styles=thumbnail"
       alt="Staff Photo" class="staff-thumbnail">
  <h3>John Doe</h3>
  <p>Executive Director</p>
</div>
```

### Advanced Usage

#### Multiple Image Sizes

Display different sizes for different contexts:

```html
<!-- Mobile-friendly responsive images -->
<picture>
  <source media="(max-width: 480px)"
          srcset="/civicrm/contact/imagefile?photo=image.jpg&image_styles=small">
  <source media="(max-width: 768px)"
          srcset="/civicrm/contact/imagefile?photo=image.jpg&image_styles=medium">
  <img src="/civicrm/contact/imagefile?photo=image.jpg&image_styles=large"
       alt="Contact Photo">
</picture>
```

## Support and Contributing

- **Issues:** Report bugs on [GitHub Issues](https://github.com/Skvare/com.skvare.imageresize/issues)
- **Documentation:** Additional guides in the project wiki
- **Contributing:** Pull requests welcome! Follow CiviCRM coding standards
- **Community:** Join discussions on [CiviCRM Chat](https://chat.civicrm.org)

## Credits

Developed by [Skvare, LLC](https://skvare.com) for the CiviCRM community.

## About Skvare

Skvare LLC specializes in CiviCRM development, Drupal integration, and providing technology solutions for nonprofit organizations, professional societies, membership-driven associations, and small businesses. We are committed to developing open source software that empowers our clients and the wider CiviCRM community.

**Contact Information**:
- Website: [https://skvare.com](https://skvare.com)
- Email: info@skvare.com
- GitHub: [https://github.com/Skvare](https://github.com/Skvare)

## Support

[Contact us](https://skvare.com/contact) for support or to learn more.

---

## Related Extensions

You might also be interested in other Skvare CiviCRM extensions:

- **Database Custom Field Check**: Prevents adding custom fields when table limits are reached
- **Registration Button Label**: Customize button labels on event registration pages

For a complete list of our open source contributions, visit our [GitHub organization page](https://github.com/Skvare).
