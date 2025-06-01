# food-king
##.............Documentation ..............


## Getting started

To make it easy for you to get started with GitLab, here's a list of recommended next steps.

Already a pro? Just edit this README.md and make it your own. Want to make it easy? [Use the template at the bottom](#editing-this-readme)!

## Add your files

- [ ] [Create](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#create-a-file) or [upload](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#upload-a-file) files
- [ ] [Add files using the command line](https://docs.gitlab.com/ee/gitlab-basics/add-file.html#add-a-file-using-the-command-line) or push an existing Git repository with the following command:

```
cd existing_repo
git remote add origin https://gitlab.com/inilabs/food-king.git
git branch -M main
git push -uf origin main
```

## Integrate with your tools

- [ ] [Set up project integrations](https://gitlab.com/inilabs/food-king/-/settings/integrations)

## Collaborate with your team

- [ ] [Invite team members and collaborators](https://docs.gitlab.com/ee/user/project/members/)
- [ ] [Create a new merge request](https://docs.gitlab.com/ee/user/project/merge_requests/creating_merge_requests.html)
- [ ] [Automatically close issues from merge requests](https://docs.gitlab.com/ee/user/project/issues/managing_issues.html#closing-issues-automatically)
- [ ] [Enable merge request approvals](https://docs.gitlab.com/ee/user/project/merge_requests/approvals/)
- [ ] [Automatically merge when pipeline succeeds](https://docs.gitlab.com/ee/user/project/merge_requests/merge_when_pipeline_succeeds.html)

## Test and Deploy

Use the built-in continuous integration in GitLab.

- [ ] [Get started with GitLab CI/CD](https://docs.gitlab.com/ee/ci/quick_start/index.html)
- [ ] [Analyze your code for known vulnerabilities with Static Application Security Testing(SAST)](https://docs.gitlab.com/ee/user/application_security/sast/)
- [ ] [Deploy to Kubernetes, Amazon EC2, or Amazon ECS using Auto Deploy](https://docs.gitlab.com/ee/topics/autodevops/requirements.html)
- [ ] [Use pull-based deployments for improved Kubernetes management](https://docs.gitlab.com/ee/user/clusters/agent/)
- [ ] [Set up protected environments](https://docs.gitlab.com/ee/ci/environments/protected_environments.html)

***

# Editing this README

When you're ready to make this README your own, just edit this file and use the handy template below (or feel free to structure it however you want - this is just a starting point!). Thank you to [makeareadme.com](https://www.makeareadme.com/) for this template.

## Suggestions for a good README
Every project is different, so consider which of these sections apply to yours. The sections used in the template are suggestions for most open source projects. Also keep in mind that while a README can be too long and detailed, too long is better than too short. If you think your README is too long, consider utilizing another form of documentation rather than cutting out information.

## Name
Choose a self-explaining name for your project.

## Description
Let people know what your project can do specifically. Provide context and add a link to any reference visitors might be unfamiliar with. A list of Features or a Background subsection can also be added here. If there are alternatives to your project, this is a good place to list differentiating factors.

## Badges
On some READMEs, you may see small images that convey metadata, such as whether or not all the tests are passing for the project. You can use Shields to add some to your README. Many services also have instructions for adding a badge.

## Visuals
Depending on what you are making, it can be a good idea to include screenshots or even a video (you'll frequently see GIFs rather than actual videos). Tools like ttygif can help, but check out Asciinema for a more sophisticated method.

## Installation
Within a particular ecosystem, there may be a common way of installing things, such as using Yarn, NuGet, or Homebrew. However, consider the possibility that whoever is reading your README is a novice and would like more guidance. Listing specific steps helps remove ambiguity and gets people to using your project as quickly as possible. If it only runs in a specific context like a particular programming language version or operating system or has dependencies that have to be installed manually, also add a Requirements subsection.

## Usage
Use examples liberally, and show the expected output if you can. It's helpful to have inline the smallest example of usage that you can demonstrate, while providing links to more sophisticated examples if they are too long to reasonably include in the README.

## Support
Tell people where they can go to for help. It can be any combination of an issue tracker, a chat room, an email address, etc.

## Roadmap
If you have ideas for releases in the future, it is a good idea to list them in the README.

## Contributing
State if you are open to contributions and what your requirements are for accepting them.

For people who want to make changes to your project, it's helpful to have some documentation on how to get started. Perhaps there is a script that they should run or some environment variables that they need to set. Make these steps explicit. These instructions could also be useful to your future self.

You can also document commands to lint the code or run tests. These steps help to ensure high code quality and reduce the likelihood that the changes inadvertently break something. Having instructions for running tests is especially helpful if it requires external setup, such as starting a Selenium server for testing in a browser.

## Authors and acknowledgment
Show your appreciation to those who have contributed to the project.

## License
For open source projects, say how it is licensed.

## Project status
If you have run out of energy or time for your project, put a note at the top of the README saying that development has slowed down or stopped completely. Someone may choose to fork your project or volunteer to step in as a maintainer or owner, allowing your project to keep going. You can also make an explicit request for maintainers.

# Restaurant Management System - Authentication Documentation

## Table of Contents
1. [Authentication Overview](#authentication-overview)
2. [User Types and Roles](#user-types-and-roles)
3. [Authentication Flow](#authentication-flow)
4. [API Endpoints](#api-endpoints)
5. [Frontend Components](#frontend-components)
6. [Security Features](#security-features)

## Authentication Overview

The application uses a modern authentication system combining Laravel's backend authentication with Vue.js frontend, utilizing Laravel Sanctum for API token authentication.

### Key Features
- Token-based authentication
- Role-based access control
- Multi-tenant support (branch-based)
- Guest user support
- Password recovery system
- Phone verification system

## User Types and Roles

### Available Roles
1. **Admin**
   - Full system access
   - Branch management
   - User management

2. **Customer**
   - Order placement
   - Profile management
   - Order history

3. **Delivery Boy**
   - Order delivery management
   - Delivery status updates

4. **Waiter**
   - Table service management
   - Order taking

5. **Chef**
   - Kitchen order management
   - Food preparation tracking

6. **Branch Manager**
   - Branch-specific management
   - Staff management
   - Local inventory

7. **POS Operator**
   - Point of sale operations
   - Order processing
   - Payment handling

8. **Staff**
   - Basic operational access
   - Limited functionality

## Authentication Flow

### 1. Regular User Login Process

#### Frontend (LoginComponent.vue)
```javascript
login: function () {
    this.loading.isActive = true;
    this.$store.dispatch('login', this.form).then((res) => {
        // Store authentication token
        // Store user data
        // Load permissions
        // Handle cart state
        // Redirect based on user role
    }).catch((err) => {
        this.errors = err.response.data.errors;
    });
}
```

#### Backend (LoginController.php)
```php
public function login(Request $request)
{
    // 1. Validate input
    $validator = Validator::make($request->all(), [
        'email'    => ['required', 'string', 'email', 'max:255'],
        'password' => ['required', 'string', 'min:6'],
    ]);

    // 2. Check user status
    $request->merge(['status' => Status::ACTIVE]);

    // 3. Attempt authentication
    if (!Auth::guard('web')->attempt($request->only('email', 'password', 'status'))) {
        return new JsonResponse(['errors' => ['validation' => 'Invalid credentials']], 400);
    }

    // 4. Generate token and return user data
    $user = User::where('email', $request['email'])->first();
    $token = $user->createToken('auth_token')->plainTextToken;

    // 5. Return response with user data and permissions
    return new JsonResponse([
        'token'      => $token,
        'user'       => new UserResource($user),
        'permission' => PermissionResource::collection($permissions),
        // ... other data
    ]);
}
```

### 2. Guest User Registration Process

#### Step 1: Phone Number Verification
```php
// GuestSignupController.php
public function otp(GuestSignupPhoneRequest $request)
{
    // 1. Validate phone number
    // 2. Generate OTP
    // 3. Send OTP via SMS
    $this->otpManagerService->otp($request);
}
```

#### Step 2: OTP Verification
```php
public function verify(VerifyPhoneRequest $request)
{
    // 1. Verify OTP code
    if ($this->otpManagerService->verify($request)) {
        // 2. Create guest user account
        return $this->register([
            'code'  => $request->code,
            'phone' => $request->phone,
            'token' => $request->token
        ]);
    }
}
```

#### Step 3: Guest Account Creation
```php
private function register($array)
{
    // 1. Create guest user
    $user = User::create([
        'name'         => "Guest User",
        'username'     => Str::slug($name) . rand(11111, 99999),
        'phone'        => $array['phone'],
        'country_code' => $array['code'],
        'is_guest'     => Ask::YES,
        'password'     => Hash::make(rand(111111, 999999))
    ]);

    // 2. Assign customer role
    $user->assignRole(EnumRole::CUSTOMER);

    // 3. Generate authentication token
    $token = $user->createToken('auth_token')->plainTextToken;

    // 4. Return user data and permissions
    return new JsonResponse([
        'token' => $token,
        'user'  => new UserResource($user),
        // ... other data
    ]);
}
```

### 3. Regular User Registration Process

#### Step 1: Phone Verification
```php
// SignupController.php
public function otp(SignupPhoneRequest $request)
{
    // 1. Validate phone number
    // 2. Generate and send OTP
    $this->otpManagerService->otp($request);
}
```

#### Step 2: Account Creation
```php
public function register(SignupRequest $request)
{
    // 1. Validate OTP if phone verification is enabled
    if (Settings::group('site')->get('site_phone_verification') == Activity::ENABLE) {
        // Verify OTP
    }

    // 2. Create user account
    $user = User::create([
        'name'     => $name,
        'email'    => $request->post('email'),
        'phone'    => $request->post('phone'),
        'password' => Hash::make($request->post('password'))
    ]);

    // 3. Assign customer role
    $user->assignRole(EnumRole::CUSTOMER);
}
```

### 4. Authentication State Management

#### Vuex Store Structure
```javascript
// store/auth.js
export default {
    state: {
        user: null,
        token: null,
        permissions: [],
        branchId: null
    },
    
    mutations: {
        SET_USER(state, user) {
            state.user = user;
        },
        SET_TOKEN(state, token) {
            state.token = token;
            localStorage.setItem('auth_token', token);
        },
        SET_PERMISSIONS(state, permissions) {
            state.permissions = permissions;
        }
    },
    
    actions: {
        async login({ commit }, credentials) {
            const response = await axios.post('/api/auth/login', credentials);
            commit('SET_USER', response.data.user);
            commit('SET_TOKEN', response.data.token);
            commit('SET_PERMISSIONS', response.data.permissions);
        },
        
        async logout({ commit }) {
            await axios.post('/api/auth/logout');
            commit('SET_USER', null);
            commit('SET_TOKEN', null);
            commit('SET_PERMISSIONS', []);
            localStorage.removeItem('auth_token');
        }
    }
};
```

### 5. Permission Management

#### Role-Permission Assignment
```php
// RoleTableSeeder.php
public function run()
{
    Role::insert([
        [
            'name'       => 'Admin',
            'guard_name' => 'sanctum'
        ],
        [
            'name'       => 'Customer',
            'guard_name' => 'sanctum'
        ],
        // ... other roles
    ]);
}
```

#### Permission Checking
```php
// Middleware example
public function handle($request, Closure $next)
{
    if (!$request->user()->hasPermissionTo($this->permission)) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    return $next($request);
}
```

### 6. Security Implementations

#### Token Management
```php
// User.php
public function createToken($name)
{
    return $this->createToken($name, ['*'])->plainTextToken;
}
```

#### Password Security
```php
// SignupRequest.php
public function rules()
{
    return [
        'password' => [
            'required',
            'min:6',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
        ]
    ];
}
```

## API Endpoints

### Authentication Endpoints

1. **Login**
   ```
   POST /api/auth/login
   Body: {
       email: string,
       password: string
   }
   ```

2. **Register**
   ```
   POST /api/auth/register
   Body: {
       name: string,
       email: string,
       phone: string,
       password: string
   }
   ```

3. **Password Reset**
   ```
   POST /api/auth/password/reset
   Body: {
       email: string,
       token: string,
       password: string
   }
   ```

### Response Format
```json
{
    "message": "string",
    "token": "string",
    "branch_id": "integer",
    "user": {
        "id": "integer",
        "name": "string",
        "email": "string",
        "role": "string"
    },
    "menu": [],
    "permission": [],
    "defaultPermission": []
}
```

## Frontend Components

### Core Authentication Components

1. **LoginComponent**
   - User login form
   - Credential validation
   - Remember me functionality
   - Demo account access

2. **SignupComponents**
   - Phone verification
   - User registration
   - Form validation

3. **PasswordResetComponents**
   - Password recovery
   - Email verification
   - New password setup

### State Management (Vuex)

```javascript
// Auth Store
state: {
    user: null,
    token: null,
    permissions: []
},
mutations: {
    SET_USER(state, user),
    SET_TOKEN(state, token),
    SET_PERMISSIONS(state, permissions)
},
actions: {
    login({ commit }, credentials),
    logout({ commit }),
    refreshToken({ commit })
}
```

## Security Features

1. **Token Management**
   - Sanctum token generation
   - Token expiration
   - Token refresh mechanism

2. **Password Security**
   - Hashed storage
   - Minimum length requirement
   - Complexity validation

3. **Role-Based Access**
   - Permission checking
   - Menu access control
   - API endpoint protection

4. **Branch-Level Security**
   - Multi-tenant data isolation
   - Branch-specific access
   - Cross-branch protection

5. **Session Management**
   - Automatic timeout
   - Single device login
   - Secure session storage

## Error Handling

### Common Error Scenarios

1. **Invalid Credentials**
   ```json
   {
       "errors": {
           "validation": "Invalid credentials"
       }
   }
   ```

2. **Inactive Account**
   ```json
   {
       "errors": {
           "validation": "Account is inactive"
       }
   }
   ```

3. **Missing Role**
   ```json
   {
       "errors": {
           "validation": "No role assigned"
       }
   }
   ```

### Error Recovery
- Automatic retry mechanism
- Session recovery
- Graceful degradation

## Best Practices

1. **Security**
   - Use HTTPS
   - Implement rate limiting
   - Validate all inputs

2. **Performance**
   - Cache permissions
   - Lazy load components
   - Optimize API calls

3. **User Experience**
   - Clear error messages
   - Loading indicators
   - Intuitive flow

## Testing

### Unit Tests
```bash
php artisan test --filter AuthenticationTest
```

### Feature Tests
```bash
php artisan test --filter LoginFeatureTest
```

## Troubleshooting

Common issues and solutions:

1. **Token Expiration**
   - Implement refresh token
   - Auto-logout on expiration
   - Clear local storage

2. **Permission Issues**
   - Check role assignment
   - Verify permission seeding
   - Clear permission cache

3. **Session Problems**
   - Clear browser cache
   - Check cookie settings
   - Verify CORS configuration
