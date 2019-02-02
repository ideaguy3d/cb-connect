import { Meteor } from 'meteor/meteor';
import { Accounts } from 'meteor/accounts-base';
import { check } from 'meteor/check';
import { _ } from 'lodash';
import { timezones } from '../../lib/data/timezones';
import { categories } from '../../lib/data/categories';
import { Entries } from '../../lib/collections/entries';
Accounts.onCreateUser((options, user) => {
  const customizedUser = Object.assign(
    {
      complete: false,
    },
    user
  );

  if (options.profile) {
    customizedUser.profile = options.profile;
  }

  return customizedUser;
});

Meteor.methods({
  'users.enroll'(data) {
    check(data, {
      category: String,
      name: String,
      oneLineIntro: String,
      lookingFor: String,
      email: String,
      timezone: String,
    });

    const timezone = _.find(timezones, { id: data.timezone });
    const category = _.find(categories, { id: data.category });
    console.log(data);
    console.log(timezone);

    // check if user exists or not.
    const actor = Accounts.findUserByEmail(data.email);
    if (actor) {
      throw new Meteor.Error('user.enroll', 'User with this email already exists.');
    } else {
      const { email, name, oneLineIntro, lookingFor } = data;
      const { id: category_id, short_lable: category_title } = category;
      const { id: tz_id, label_text: tz_title, daylight_saving, value: tz_offset } = timezone;

      // create new user
      const options = {
        email: email,
        profile: {
          name: name,
          intro: oneLineIntro,
          tz: {
            id: tz_id,
            title: tz_title,
            offset: tz_offset,
            daylight_saving: daylight_saving,
          },
          categories: [
            {
              id: category_id,
              title: category_title,
              note: lookingFor,
            },
          ],
        },
        complete: false,
      };

      // create a user
      const userId = Accounts.createUser(options);

      if (userId) {
        // create new entry
        const entry = {
          user_id: userId,
          category: {
            id: category_id,
            title: category_title,
          },
          tz: {
            id: tz_id,
            title: tz_title,
            offset: tz_offset,
            daylight_saving: daylight_saving,
          },
          note: lookingFor,
          verified: false,
          active: false,
          preferences: [],
          current_match: [],
          previous_matches: [],
        };

        Entries.insert(entry);
        // send welcome email
        try {
          return Accounts.sendEnrollmentEmail(userId);
        } catch (e) {
          console.log(e);
        }
      }
    }
  },
});