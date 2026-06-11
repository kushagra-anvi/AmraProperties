# Amra Property CRM Implementation Plan

## Current Foundation

- Laravel CRM app created as a separate project from the public static website.
- SQLite is configured for local development; production can move to MySQL.
- Core CRM schema is in place for B2B leads, B2C leads, partners, sales people, lead sharing, follow-ups, status history, and package reminders.
- A basic dashboard route exists at `/dashboard`.

## Phase 1: Admin Foundation

- Add authentication.
- Add role-based access for Super Admin, Admin, Sales Team, and Analyst.
- Create the shared CRM layout with sidebar navigation.
- Add date filter helpers for Today, Yesterday, Last 7 Days, Last 30 Days, and Custom Range.

## Phase 2: B2B Lead Management

- Build B2B lead listing, create, edit, and detail pages.
- Add assignment to sales person.
- Add status update workflow: New, Contacted, Qualified, Not Interested, Follow-up, Free Listing, Paid Listing, Converted.
- Store status logs and follow-up notes.
- Add CSV upload.
- Add platform-wise conversion analytics.

## Phase 3: B2C Buyer Leads

- Build B2C lead listing, create, edit, and detail pages.
- Add buyer filters for budget, location, property type, configuration, city, source, and date range.
- Add share-to-agent/developer workflow.
- Track Shared To, Shared By, and Shared Date.
- Add platform performance analytics.

## Phase 4: Agent And Developer Tracking

- Build partner listing, create, edit, and detail pages.
- Track package: Free, Starter, Growth.
- Track package purchase date and renewal date.
- Show package expiring in 7 days.
- Track leads received by partner with date filters.

## Phase 5: Sales Team Tracking

- Build sales team listing and profile pages.
- Show assigned B2B leads.
- Track lead assigned count, contacted count, qualified count, free listing count, paid listing count, and total sale.
- Add free and paid conversion ratios.

## Phase 6: Reports And Dashboard

- Add dashboard cards for total B2B leads, total B2C leads, source-wise leads, daily leads, monthly leads, and conversion ratio.
- Add sales analytics for best sales person, total calls, paid conversions, and free conversions.
- Add partner analytics for active agents, developers, leads shared, and package expiry alerts.
- Add graphs for platform leads, conversion, ROI, daily leads, and monthly conversion.

## Website Integration

- Connect the existing public website contact form to the CRM as B2C website leads.
- Add listing inquiry forms later if individual property pages need lead capture.
- Add Meta and Google lead integrations after the manual CRM workflow is stable.
