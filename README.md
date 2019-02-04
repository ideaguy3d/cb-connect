## CB Connect

### What it is
CodeBuddies' mission is to help connect people who can _help each other_ become better at software development. So far we've built an open-sourced platform that allows people to schedule remote code pairing sessions, feel less alone/be productive in a 24/7 silent coworking hangout, etc. MOST of the activity in the community occurs on Slack, where people post questions -- sometimes technical, sometimes career-related. 

**The problem:**

Many people are intimidated by Slack, and too shy to post. 

Or they join, see that a channel they're interested in looks quiet (because we're on the Slack free plan, and messages get archived quickly), feel discouraged and leave. Or they don't see the post someone shared a while back about a project they're looking for. 

**The solution:**

CB Connect is meant to help the quiet people, who are totally looking for:
a) that accountability or pair programming partner
b) a one-time mentor willing to meet up for an hour
c) an opportunity to teach/mentor someone else 
... but don't know the right place to post on Slack, and aren't the sort of natural leaders who think nothing of organizing their own study group on CodeBuddies, or are fearless about posting their questions on Slack or scheduling a hangout on codebuddies.org/hangouts. 


### The user flow (a work-in-progress):

1/ Participant will submit a form filling out intro, what they're looking for (one of 7 categories), and further details

2/ Matcher will click the participant's card, then click another card, which will fire off an email and a notification to both parties. (After the MVP, we might have the "system" calculate some points and do auto-matching)

3/ Each party can accept or reject the match (if they reject, can leave an optional comment about why). If they accept the match, they can decide to go off and jump on a call, pair together, invite each other to a Github repo, or whatever they decide.

4/ People can create new cards at any point (e.g. you could at the same time be looking for feedback/advice, AND be looking for an accountability partner)

## How to contribute

0. Join Discord at https://discord.gg/yvtBmEW and ask any questions in the #cb-connect-questions channel.
1. Fork this repository and download your copy with `git clone [YOUR_PATH]`. You can also request to be added as a collaborator.
2. Install meteor: https://www.meteor.com/install
3. Before you start up the app, make sure to `meteor npm install`.
4. Start the app with the following command: `meteor --settings dev-settings.json`

Feel free to file new issues. We're looking for design, UX, and code (any: React, Javascript, Meteor, MongoDB) help.

If you see an issue under the `help wanted` category that you'd like to tackle, please comment that you're working on it, and create a new branch for the issue.

Once the prototype is launched, we'll also need volunteer moderators/matchers. 

Please submit new pull requests against the `staging` branch.

### How to login on localhost
After you create a new user by submitting an entry, you'll be able to log in by typing in the email you used to apply, and "password" for the password. 

If you want to get the verification email, please create an API key in Sparkpost and put it into line 14 in dev-settings.json. 
