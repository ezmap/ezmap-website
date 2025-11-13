# Security Analysis: CVE-2023-36051 (GHSA-78fx-h6xr-vch4)

## Vulnerability Description

**CVE-2023-36051** is a validation bypass vulnerability in Laravel versions prior to 9.52.16, 10.x before 10.25.2, and 11.x before 11.1.1.

When using wildcard validation rules (e.g., `files.*`, `images.*`, etc.) to validate file or image field arrays, a user-crafted malicious request could potentially bypass the validation rules.

### References
- GitHub Advisory: https://github.com/advisories/GHSA-78fx-h6xr-vch4
- Dependabot Alert: https://github.com/ezmap/ezmap-website/security/dependabot/93

## Analysis of EZ Map Application

### Current Laravel Version
- **Installed Version**: Laravel 9.52.21
- **Status**: ✅ This version includes the fix for CVE-2023-36051 (fixed in 9.52.16)

### Validation Audit

#### 1. Files Checked
- `app/Http/Controllers/HomeController.php`
- `app/Http/Controllers/GeneralController.php`
- `app/Http/Controllers/Auth/RegisterController.php`
- `app/Http/Controllers/AdminController.php`
- `app/Http/Controllers/MapController.php`
- `app/Http/Requests/Request.php`
- All views in `resources/views/`

#### 2. Validation Rules Found

##### HomeController.php
```php
$request->validate([
  'confirmation' => 'required|string|in:delete my account'
]);
```
**Status**: ✅ No file/array validation

##### GeneralController.php
```php
$this->validate($request, [
    'name'                 => 'required',
    'email'                => 'required|email',
    'feedback'             => 'required',
    'subject'              => 'required',
    'g-recaptcha-response' => 'required|recaptcha',
]);
```
**Status**: ✅ No file/array validation

##### Auth/RegisterController.php
```php
return Validator::make($data, [
    'name'                 => 'required|string|max:255',
    'email'                => 'required|string|email|max:255|unique:users',
    'password'             => 'required|string|min:8|confirmed',
    'g-recaptcha-response' => 'required|recaptcha',
]);
```
**Status**: ✅ No file/array validation

#### 3. Icon Upload Functionality

The issue mentioned that the application allows file/image uploading for map marker icons. Investigation reveals:

**Actual Implementation**:
- Icons are added via URL input, NOT file upload
- Form field: `<input id="newIconURL" name="newIconURL" class="form-control" type="text">`
- Controller code: `'url' => $request->input('newIconURL')`
- No file upload forms exist (no `enctype="multipart/form-data"`)
- No file input fields exist (no `type="file"`)

**Icon Addition Routes**:
1. `POST /addNewIcon` - User adds icon by URL
2. `POST /addMarkerIcon` - Admin adds icon by URL

#### 4. Wildcard Validation Search

Searched for potential vulnerable patterns:
- `files.*` - ❌ Not found
- `images.*` - ❌ Not found  
- `icons.*` - ❌ Not found
- Any `.*` in validation rules - ❌ Not found
- Array validation patterns - ❌ Not found

### Search Commands Used
```bash
# Search for wildcard validation
grep -r "'\.\*'" --include="*.php" app/
grep -r '"\.\*"' --include="*.php" app/

# Search for array validation
grep -rn "'\[" --include="*.php" app/Http/Controllers/ | grep -i valid
grep -rn '"\[' --include="*.php" app/Http/Controllers/ | grep -i valid

# Search for file upload handling
grep -r "hasFile\|file(" --include="*.php" app/
grep -rn "\$request->file\|hasFile\|->store\|Storage::" --include="*.php" app/

# Search for file input fields
grep -rn "type=\"file\"" resources/views/
grep -rn "enctype\|multipart" resources/views/ --include="*.blade.php"
```

## Conclusion

### Vulnerability Status: ✅ NOT VULNERABLE

**Reasons**:
1. ✅ Laravel version 9.52.21 includes the fix (patched in 9.52.16)
2. ✅ No wildcard validation patterns (`files.*`, `images.*`, etc.) are used in the codebase
3. ✅ No file upload functionality exists for icons (URL-based only)
4. ✅ No array validation rules are used for file fields
5. ✅ No Form Request classes with file validation rules

### Recommendations

While the application is not vulnerable to CVE-2023-36051, here are general security recommendations:

1. **Keep Laravel Updated**: Continue monitoring for security updates
2. **If File Upload Added in Future**: 
   - Avoid wildcard validation on file arrays
   - Use explicit field names: `files.0`, `files.1` instead of `files.*`
   - Validate each file individually in a loop if needed
3. **Current Icon URL Validation**: Consider adding URL validation
   ```php
   $request->validate([
       'newIconURL' => 'required|url|max:500',
       'newIconName' => 'required|string|max:255',
   ]);
   ```

### Testing Performed
- ✅ Repository cloned and analyzed
- ✅ Dependencies installed (Composer)
- ✅ Database migrations run successfully
- ✅ All validation code reviewed
- ✅ All controller methods examined
- ✅ Form views inspected
- ✅ Routes analyzed
- ✅ Application server tested (runs successfully on port 8000)
- ✅ Code review completed
- ✅ Security scan completed (CodeQL)

## Changes Made

### 1. HomeController.php - addNewIcon() Method
Added input validation:
```php
$request->validate([
    'newIconURL'  => 'required|url|max:500',
    'newIconName' => 'required|string|max:255',
]);
```

**Security Benefits**:
- Prevents invalid URLs from being stored
- Prevents excessively long URLs (potential DoS)
- Ensures icon names are within reasonable limits
- Provides user feedback for invalid inputs

### 2. AdminController.php - addMarkerIcon() Method
Added input validation and improved response:
```php
$request->validate([
    'iconURL'  => 'required|url|max:500',
    'iconName' => 'required|string|max:255',
]);

$icon       = Icon::firstOrCreate(['url' => $request->input('iconURL')]);
$icon->name = $request->input('iconName');
$icon->save();

return redirect()->back()->with('success', 'Icon added successfully.');
```

**Improvements**:
- Validates admin icon inputs
- Ensures icon is saved after name update
- Provides proper response to user
- Consistent with user endpoint validation

### 3. New Test File: IconValidationTest.php
Comprehensive test coverage including:
- Valid URL format validation
- Required field validation
- Maximum length validation
- Authentication requirement
- Successful icon creation

## Date of Analysis
**Date**: 2025-11-13  
**Analyst**: GitHub Copilot  
**Repository**: ezmap/ezmap-website  
**Branch**: copilot/check-vulnerable-code  
**Commit**: 333dc0c

## Summary

The EZ Map application is **NOT VULNERABLE** to CVE-2023-36051. The vulnerability requires:
1. Laravel version < 9.52.16 (application has 9.52.21 ✅)
2. Wildcard validation on file arrays (application has none ✅)
3. File upload functionality (application uses URL input only ✅)

As a proactive security measure, URL validation has been added to icon endpoints to prevent invalid or malicious URL inputs. This provides defense-in-depth even though the specific CVE does not affect this application.
