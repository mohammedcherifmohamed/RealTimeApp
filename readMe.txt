🔁 Example full flow:

You join:

Laravel sends the full list of connected users to your .here(users) callback.

Someone else joins:

You get .joining(user) with that new person’s data.

Someone leaves:

You get .leaving(user) with that person’s data, so you can remove them.
