# Frontend Documentation - Your Story Community

## Overview

Your Story Community frontend is the community platform component of the YourStory ecosystem. Built with modern React, TypeScript, Inertia.js, and Tailwind CSS, it provides an intuitive and responsive interface for users to share stories, interact with content, and manage their profiles.

### YourStory Ecosystem
- **Company Portfolio:** [yourstory-portfolio.vercel.app](https://yourstory-portfolio.vercel.app/)
- **Main Product:** [yourstory-orcin.vercel.app](https://yourstory-orcin.vercel.app/)
- **Community Platform:** This repository - Community hub for storytellers

## 🛠 Tech Stack

### Core Framework
- **React:** 19.x (Latest)
- **TypeScript:** 5.x
- **Inertia.js:** 2.x (Server-side routing)
- **Vite:** 7.2.7 (Build tool)

### UI & Styling
- **Tailwind CSS:** 3.4.3
- **PostCSS:** 8.5.6
- **Autoprefixer:** 10.4.22

### HTTP & State Management
- **Axios:** 1.x (HTTP client)
- **React Context API:** Authentication state
- **LocalStorage:** Token persistence

### Development Tools
- **ESLint:** Code linting
- **TypeScript Compiler:** Type checking
- **Vite HMR:** Hot module replacement

## 📁 Project Structure

```
resources/
├── js/
│   ├── app.tsx                    # Application entry point
│   ├── vite-env.d.ts             # TypeScript declarations
│   │
│   ├── Context/
│   │   └── AuthContext.tsx       # Authentication context & provider
│   │
│   ├── Layouts/
│   │   └── Layout.tsx            # Main layout with navbar
│   │
│   ├── Pages/
│   │   ├── Home.tsx              # Landing page
│   │   ├── Login.tsx             # Login form
│   │   ├── Register.tsx          # Registration form
│   │   ├── Stories.tsx           # Story list page
│   │   └── Story.tsx             # Story detail page
│   │
│   ├── Services/
│   │   └── api.ts                # API client service
│   │
│   └── types/
│       └── inertia.d.ts          # Inertia type definitions
│
├── css/
│   └── app.css                   # Tailwind directives
│
└── views/
    └── app.blade.php             # Laravel view template

public/
└── build/                        # Compiled assets
    ├── assets/
    │   ├── app-*.css            # Compiled CSS (28.32 KB)
    │   ├── app-*.js             # Compiled JS (382.32 KB)
    │   └── *.js                 # Page-specific chunks
    └── manifest.json             # Asset manifest
```

## 🎨 Design System

### Color Palette
- **Primary:** Indigo (600) - Main brand color
- **Secondary:** Purple (600) - Accent color
- **Success:** Green (500) - Positive actions
- **Error:** Red (500) - Error states
- **Background:** White / Slate (50, 800)
- **Text:** Gray (900) / White

### Typography
- **Font Family:** Sans-serif (system fonts)
- **Font Sizes:**
  - Display: 4xl, 3xl (Hero sections)
  - Heading: 2xl, xl (Page titles)
  - Body: base, lg (Content)
  - Small: sm, xs (Meta info)

### Spacing
- **Container:** max-w-7xl, max-w-6xl
- **Padding:** p-4, p-6, p-8
- **Margin:** m-4, m-6, m-8
- **Gap:** gap-4, gap-6, gap-8

### Components
- **Cards:** Rounded-2xl with shadow-xl
- **Buttons:** Rounded-xl with gradients
- **Inputs:** Rounded-xl with focus rings
- **Badges:** Rounded-full with bg colors

## 📄 Pages Overview

### 1. Home Page (`Home.tsx`)
**Purpose:** Landing page for unauthenticated users

**Features:**
- Hero section with gradient background
- Feature showcase (3 cards)
- Featured stories grid
- Call-to-action buttons

**Key Components:**
```tsx
- Hero: Animated badge, gradient text, CTA buttons
- Features: Grid of 3 feature cards with icons
- Stories: 3-column grid of featured stories
- Navigation: Links to login/register
```

**Styling Highlights:**
- Gradient backgrounds (indigo-to-purple)
- Hover animations on cards
- Responsive grid layout
- Badge with pulse animation

### 2. Login Page (`Login.tsx`)
**Purpose:** User authentication

**Features:**
- Email and password fields
- Form validation
- Error display
- Remember me option
- Loading state

**Form Fields:**
```tsx
- Email: email input with validation
- Password: password input with toggle visibility
- Submit: Gradient button with loading spinner
```

**API Integration:**
```typescript
await api.login({ email, password })
// Returns: { token, user }
```

**Styling Highlights:**
- Modern card design with backdrop blur
- Gradient submit button
- Error state styling
- Icon labels (📧, 🔒)

### 3. Register Page (`Register.tsx`)
**Purpose:** New user registration

**Features:**
- Name, email, password fields
- Password confirmation
- Form validation
- Error display
- Success redirect

**Form Fields:**
```tsx
- Name: text input
- Email: email input with validation
- Password: password input (min 8 chars)
- Password Confirmation: matching validation
- Submit: Gradient button
```

**API Integration:**
```typescript
await api.register({ 
  name, 
  email, 
  password, 
  password_confirmation 
})
// Returns: { token, user }
```

**Validation Rules:**
- Name: Required, string, max 255
- Email: Required, email format, unique
- Password: Required, min 8 chars, confirmed

### 4. Stories Page (`Stories.tsx`)
**Purpose:** List all stories

**Features:**
- Grid layout of stories
- Story cards with metadata
- User avatars with initials
- Like and comment counts
- Loading states
- Empty state

**Story Card Components:**
```tsx
- Thumbnail: Gradient placeholder
- Title: Story title (truncated)
- Author: User name with avatar
- Stats: Like count, comment count
- Date: Created date (formatted)
```

**API Integration:**
```typescript
await api.getStories()
// Returns: Story[]
```

**Styling Highlights:**
- 3-column responsive grid
- Card hover effects
- User avatar with first letter
- Gradient thumbnails

### 5. Story Detail Page (`Story.tsx`)
**Purpose:** Display single story with comments

**Features:**
- Story header with gradient
- Author information
- Story content
- Comments section
- Like button
- Share options

**Components:**
```tsx
- Header: Large gradient background
- Author: Avatar, name, date
- Content: Formatted story text
- Comments: Nested comment tree
- Actions: Like, share, bookmark
```

**API Integration:**
```typescript
await api.getStory(id)
// Returns: Story with user, comments, likes
```

**Styling Highlights:**
- Large gradient header
- Typography hierarchy
- Comment threading
- Action buttons

## 🔐 Authentication Flow

### AuthContext (`AuthContext.tsx`)

**State Management:**
```typescript
interface AuthContextType {
  user: User | null           // Current user
  token: string | null        // Auth token
  loading: boolean            // Loading state
  error: string | null        // Error message
  login: (email, password) => Promise<void>
  register: (name, email, password, confirmation) => Promise<void>
  logout: () => Promise<void>
  isAuthenticated: boolean    // Auth status
}
```

**Initialization:**
```typescript
// On app load
const storedToken = localStorage.getItem('auth_token')
const storedUser = localStorage.getItem('user')
if (storedToken && storedUser) {
  setToken(storedToken)
  setUser(JSON.parse(storedUser))
}
```

**Login Flow:**
```typescript
1. User submits login form
2. api.login() calls POST /api/auth/login
3. Backend validates credentials
4. Backend returns { token, user }
5. Token saved to localStorage
6. User saved to localStorage
7. Context state updated
8. Redirect to /stories
```

**Logout Flow:**
```typescript
1. User clicks logout
2. api.logout() calls POST /api/auth/logout
3. Clear localStorage (token, user)
4. Clear context state
5. Redirect to /login
```

## 🌐 API Service (`api.ts`)

### Base Configuration
```typescript
const API_BASE_URL = '/api'

axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})
```

### Request Interceptor
```typescript
// Add token to all requests
config.headers.Authorization = `Bearer ${localStorage.getItem('auth_token')}`
```

### Response Interceptor
```typescript
// Handle 401 responses
if (error.response?.status === 401) {
  localStorage.removeItem('auth_token')
  localStorage.removeItem('user')
  window.location.href = '/login'
}
```

### API Methods

#### Authentication
```typescript
// Login
api.login({ email, password })
// POST /api/auth/login
// Returns: { token, user }

// Register
api.register({ name, email, password, password_confirmation })
// POST /api/auth/register
// Returns: { token, user }

// Logout
api.logout()
// POST /api/auth/logout
// Returns: { success: true }

// Get current user
api.me()
// GET /api/auth/me
// Returns: { user }
```

#### Stories
```typescript
// Get all stories
api.getStories()
// GET /api/stories
// Returns: Story[]

// Get single story
api.getStory(id)
// GET /api/stories/{id}
// Returns: Story (with user, comments, likes)

// Create story
api.createStory({ title, content })
// POST /api/stories
// Returns: Story

// Update story
api.updateStory(id, { title, content })
// PUT /api/stories/{id}
// Returns: Story

// Delete story
api.deleteStory(id)
// DELETE /api/stories/{id}
// Returns: { success: true }
```

#### Comments
```typescript
// Get story comments
api.getComments(storyId)
// GET /api/stories/{storyId}/comments
// Returns: Comment[]

// Create comment
api.createComment(storyId, { content, parent_id })
// POST /api/stories/{storyId}/comments
// Returns: Comment

// Delete comment
api.deleteComment(commentId)
// DELETE /api/comments/{commentId}
// Returns: { success: true }
```

#### Likes
```typescript
// Toggle like
api.toggleLike(storyId)
// POST /api/stories/{storyId}/like
// Returns: { liked: boolean, like_count: number }
```

## 🎭 Layout Component (`Layout.tsx`)

### Features
- Responsive navigation bar
- User menu dropdown
- Mobile menu
- Logo and branding
- Sticky positioning

### Navigation Structure
```tsx
<nav>
  <Logo />
  <NavigationLinks>
    - Home
    - Stories
    - About
  </NavigationLinks>
  <UserMenu>
    {authenticated ? (
      - Profile
      - Settings
      - Logout
    ) : (
      - Login
      - Register
    )}
  </UserMenu>
</nav>
```

### Styling Features
- Backdrop blur effect
- Gradient accent border
- Hover transitions
- User avatar with initials
- Responsive breakpoints

## 🎨 Styling Guidelines

### Tailwind Classes
**Buttons:**
```css
bg-gradient-to-r from-indigo-600 to-purple-600
hover:from-indigo-700 hover:to-purple-700
text-white font-semibold
rounded-xl px-6 py-3
shadow-lg hover:shadow-xl
transition-all duration-200
```

**Cards:**
```css
bg-white dark:bg-slate-800
rounded-2xl shadow-xl
border border-gray-200 dark:border-slate-700
p-8
hover:shadow-2xl transition-shadow
```

**Inputs:**
```css
w-full px-4 py-3
border border-gray-300 dark:border-slate-600
rounded-xl
focus:ring-2 focus:ring-indigo-500
focus:border-transparent
bg-white dark:bg-slate-700
text-gray-900 dark:text-white
```

**Gradients:**
```css
bg-gradient-to-br from-indigo-50 to-purple-50
bg-gradient-to-r from-indigo-600 to-purple-600
```

## 🚀 Build & Development

### Development Server
```bash
npm run dev
# Vite dev server at http://localhost:5173
# With HMR (Hot Module Replacement)
```

### Production Build
```bash
npm run build
# Output: public/build/
# - CSS: ~28.32 KB (gzipped: ~5.12 KB)
# - JS: ~382.32 KB (gzipped: ~124.53 KB)
```

### Build Configuration (`vite.config.js`)
```javascript
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.tsx'],
      refresh: true,
    }),
    react(),
  ],
  resolve: {
    alias: {
      '@': '/resources/js',
    },
  },
})
```

### TypeScript Configuration (`tsconfig.json`)
```json
{
  "compilerOptions": {
    "target": "ES2020",
    "module": "ESNext",
    "lib": ["ES2020", "DOM", "DOM.Iterable"],
    "jsx": "react-jsx",
    "moduleResolution": "bundler",
    "paths": {
      "@/*": ["./resources/js/*"]
    },
    "strict": true,
    "esModuleInterop": true,
    "skipLibCheck": true
  }
}
```

## 📦 Dependencies

### Production Dependencies
```json
{
  "@inertiajs/react": "^2.0.0",
  "axios": "^1.7.9",
  "react": "^19.0.0",
  "react-dom": "^19.0.0"
}
```

### Development Dependencies
```json
{
  "@vitejs/plugin-react": "^4.3.4",
  "tailwindcss": "^3.4.3",
  "typescript": "^5.6.3",
  "vite": "^7.2.7",
  "laravel-vite-plugin": "^1.1.1",
  "postcss": "^8.5.6",
  "autoprefixer": "^10.4.22"
}
```

## 🔧 Configuration Files

### Tailwind Config (`tailwind.config.js`)
```javascript
export default {
  content: [
    "./index.html",
    "./resources/**/*.{js,ts,jsx,tsx}",
    "./resources/**/*.blade.php"
  ],
  theme: {
    extend: {
      colors: {
        // Custom colors if needed
      },
    },
  },
  plugins: [],
}
```

### PostCSS Config (`postcss.config.js`)
```javascript
export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
```

## 🐛 Troubleshooting

### Common Issues

**1. Tailwind styles not applying**
```bash
# Check content paths in tailwind.config.js
# Rebuild CSS
npm run build
```

**2. TypeScript errors**
```bash
# Check tsconfig.json
# Ensure all types are installed
npm install --save-dev @types/react @types/react-dom
```

**3. Vite build errors**
```bash
# Clear Vite cache
rm -rf node_modules/.vite
# Rebuild
npm run build
```

**4. API requests failing**
```bash
# Check API base URL in api.ts
# Verify token in localStorage
# Check backend is running
```

**5. Auth not persisting**
```bash
# Check localStorage in browser DevTools
# Verify token is saved on login
# Check AuthContext initialization
```

## 🎯 Best Practices

### Component Structure
```tsx
// 1. Imports
import React from 'react'
import { Link } from '@inertiajs/react'

// 2. Type definitions
interface Props {
  title: string
}

// 3. Component
export default function Component({ title }: Props) {
  // 4. State
  const [state, setState] = useState()
  
  // 5. Effects
  useEffect(() => {}, [])
  
  // 6. Handlers
  const handleClick = () => {}
  
  // 7. Render
  return <div>{title}</div>
}
```

### State Management
- Use AuthContext for global auth state
- Use local state for component-specific data
- Lift state up when sharing between components
- Use localStorage for persistence

### Error Handling
```typescript
try {
  await api.someMethod()
} catch (error: any) {
  if (error.response?.data?.errors) {
    setErrors(error.response.data.errors)
  } else {
    setErrors({ form: error.response?.data?.message })
  }
}
```

### Loading States
```tsx
const [loading, setLoading] = useState(false)

const handleSubmit = async () => {
  setLoading(true)
  try {
    await api.someMethod()
  } finally {
    setLoading(false)
  }
}

return (
  <button disabled={loading}>
    {loading ? 'Loading...' : 'Submit'}
  </button>
)
```

## 📱 Responsive Design

### Breakpoints (Tailwind)
- **sm:** 640px (Mobile landscape)
- **md:** 768px (Tablet)
- **lg:** 1024px (Desktop)
- **xl:** 1280px (Large desktop)
- **2xl:** 1536px (Extra large)

### Mobile-First Approach
```tsx
// Base styles for mobile
className="text-sm p-4"

// Add styles for larger screens
className="text-sm md:text-base p-4 md:p-6"

// Grid responsive
className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3"
```

## 🚀 Deployment

### Build for Production
```bash
# 1. Install dependencies
npm install

# 2. Build assets
npm run build

# 3. Assets output to public/build/
# Laravel will serve these automatically
```

### Environment Variables
Frontend uses Laravel environment, no separate `.env` needed for React.

### CDN Integration (Optional)
If using CDN for assets, update `config/app.php`:
```php
'asset_url' => env('ASSET_URL', null),
```

## 📚 Further Reading

- **React Documentation:** https://react.dev
- **Inertia.js Guide:** https://inertiajs.com
- **Tailwind CSS Docs:** https://tailwindcss.com
- **Vite Documentation:** https://vite.dev
- **TypeScript Handbook:** https://www.typescriptlang.org/docs

## 🤝 Contributing

When contributing to the frontend:
1. Follow the existing code style
2. Use TypeScript for all new components
3. Add proper type definitions
4. Test on multiple screen sizes
5. Ensure accessibility (ARIA labels, keyboard navigation)
6. Write meaningful commit messages

## � Related Projects

- **[YourStory App](https://yourstory-orcin.vercel.app/)** - Main storytelling platform
- **[YourStory Portfolio](https://yourstory-portfolio.vercel.app/)** - Company website
- **[Community Repository](https://github.com/Bangkah/yourstory-community)** - This project

## �📝 License

MIT License - see LICENSE file for details
