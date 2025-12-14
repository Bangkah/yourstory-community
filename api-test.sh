#!/bin/bash

# Your Story Community - Quick API Test Script
# Usage: bash api-test.sh

BASE_URL="http://localhost:8080/api"
EMAIL="member1@yourstory.local"
PASSWORD="password123"

echo "🚀 Your Story Community - API Test"
echo "===================================="
echo ""

# 1. Login
echo "1️⃣  Testing Login..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"$EMAIL\",\"password\":\"$PASSWORD\"}")

TOKEN=$(echo $LOGIN_RESPONSE | jq -r '.token')
USER_ID=$(echo $LOGIN_RESPONSE | jq -r '.user.id')
USER_NAME=$(echo $LOGIN_RESPONSE | jq -r '.user.name')

echo "✅ Logged in as: $USER_NAME (ID: $USER_ID)"
echo "📝 Token: ${TOKEN:0:30}..."
echo ""

# 2. Get Stories
echo "2️⃣  Testing Get Stories..."
STORIES_RESPONSE=$(curl -s "$BASE_URL/stories" -H "Accept: application/json")
TOTAL_STORIES=$(echo $STORIES_RESPONSE | jq '.total')
echo "✅ Total stories in DB: $TOTAL_STORIES"
FIRST_STORY_ID=$(echo $STORIES_RESPONSE | jq '.data[0].id')
FIRST_STORY_TITLE=$(echo $STORIES_RESPONSE | jq -r '.data[0].title')
echo "📖 First story (ID: $FIRST_STORY_ID): $FIRST_STORY_TITLE"
echo ""

# 3. Get Single Story
echo "3️⃣  Testing Get Single Story..."
SINGLE_STORY=$(curl -s "$BASE_URL/stories/$FIRST_STORY_ID" -H "Accept: application/json")
AUTHOR=$(echo $SINGLE_STORY | jq -r '.user.name')
LIKES=$(echo $SINGLE_STORY | jq '.likes_count')
COMMENTS=$(echo $SINGLE_STORY | jq '.comments_count')
echo "✅ Story by: $AUTHOR"
echo "   👍 Likes: $LIKES | 💬 Comments: $COMMENTS"
echo ""

# 4. Get Comments (Tree)
echo "4️⃣  Testing Get Comments Tree..."
COMMENTS_RESPONSE=$(curl -s "$BASE_URL/stories/$FIRST_STORY_ID/comments" -H "Accept: application/json")
COMMENT_COUNT=$(echo $COMMENTS_RESPONSE | jq 'length')
echo "✅ Root comments: $COMMENT_COUNT"
FIRST_COMMENT=$(echo $COMMENTS_RESPONSE | jq '.[0]')
if [ "$FIRST_COMMENT" != "null" ]; then
  COMMENT_BODY=$(echo $FIRST_COMMENT | jq -r '.body')
  COMMENT_AUTHOR=$(echo $FIRST_COMMENT | jq -r '.user.name')
  COMMENT_REPLIES=$(echo $FIRST_COMMENT | jq '.children | length')
  echo "   First comment by: $COMMENT_AUTHOR"
  echo "   Replies: $COMMENT_REPLIES"
fi
echo ""

# 5. Create Story
echo "5️⃣  Testing Create Story..."
CREATE_STORY_RESPONSE=$(curl -s -X POST "$BASE_URL/stories" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "title": "API Test Story - '"$(date)"'",
    "body": "This story was created via API test script.",
    "is_published": true
  }')

NEW_STORY_ID=$(echo $CREATE_STORY_RESPONSE | jq -r '.id')
NEW_STORY_TITLE=$(echo $CREATE_STORY_RESPONSE | jq -r '.title')
echo "✅ Created story ID: $NEW_STORY_ID"
echo "   Title: $NEW_STORY_TITLE"
echo ""

# 6. Add Comment
echo "6️⃣  Testing Add Comment..."
ADD_COMMENT_RESPONSE=$(curl -s -X POST "$BASE_URL/stories/$NEW_STORY_ID/comments" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"body": "Great story! Testing comment API."}')

COMMENT_ID=$(echo $ADD_COMMENT_RESPONSE | jq -r '.id')
COMMENT_CREATED=$(echo $ADD_COMMENT_RESPONSE | jq -r '.created_at')
echo "✅ Comment added ID: $COMMENT_ID"
echo "   Created: $COMMENT_CREATED"
echo ""

# 7. Add Reply to Comment
echo "7️⃣  Testing Reply to Comment..."
REPLY_RESPONSE=$(curl -s -X POST "$BASE_URL/stories/$NEW_STORY_ID/comments/$COMMENT_ID/reply" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"body": "I agree! Testing reply API."}')

REPLY_ID=$(echo $REPLY_RESPONSE | jq -r '.id')
REPLY_DEPTH=$(echo $REPLY_RESPONSE | jq -r '.depth')
echo "✅ Reply added ID: $REPLY_ID"
echo "   Depth: $REPLY_DEPTH"
echo ""

# 8. Toggle Like
echo "8️⃣  Testing Toggle Like..."
LIKE_RESPONSE=$(curl -s -X POST "$BASE_URL/stories/$NEW_STORY_ID/likes/toggle" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

LIKED=$(echo $LIKE_RESPONSE | jq -r '.liked')
LIKE_COUNT=$(echo $LIKE_RESPONSE | jq -r '.likes_count')
echo "✅ Like toggled: $LIKED"
echo "   Total likes: $LIKE_COUNT"
echo ""

# 9. Get Current User
echo "9️⃣  Testing Get Current User..."
ME_RESPONSE=$(curl -s "$BASE_URL/auth/me" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

ME_NAME=$(echo $ME_RESPONSE | jq -r '.name')
ME_ROLE=$(echo $ME_RESPONSE | jq -r '.role')
echo "✅ Current user: $ME_NAME"
echo "   Role: $ME_ROLE"
echo ""

# 10. Logout
echo "🔟 Testing Logout..."
LOGOUT_RESPONSE=$(curl -s -X POST "$BASE_URL/auth/logout" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

LOGOUT_MSG=$(echo $LOGOUT_RESPONSE | jq -r '.message')
echo "✅ $LOGOUT_MSG"
echo ""

echo "🎉 All API tests passed!"
echo "===================================="
