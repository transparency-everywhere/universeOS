The universeOS Development Roadmap
----------------------------------

1. Get a nice API
    complete encrypted communication via js
    RESTful
    Documented

2. Get File Encryption done



Security
----------
We try to get the script as secure as possible. It is important to point out the most important things we need to take care of:
    1. Javascript Cryptography
    2. A good Cryptoprotocoll which allows
        A Public Key Infrastructure
        Instant Messaging (with Groups)
        An easy exchange of algorithms
    3. Well written and well documented code


Advertising
------------
We think that the universeOS should not be a comerical project. But we also now, that we are trying to compete with services which gather personal data and which can generete enourmous profits with personalized advertising services. I think we should look for ways of advertising methods, which are working without personalized data. (Nic, please discuss!)



=Recent=
----------

    get rid of
        mysql_query
        $.post, $.ajax
        api.php, doit.php, showComment.php
        thousend different column names for user (author, owner, creator, user etc) instead `user`
        chat.show(), feed.show(), buddylist.show() etc. use application.show('buddylist') instead

-add ftp, dropbox etc to the filesystem

-XSS
    check php classes for sanitizeText()

-Authorize
    @sec
    Folders
        create,update,delete
    Collections
        create,update,delete
    Files
        create,update,delete
    Links
        create,update,delete
    Feeds
        create,update,delete
    Comments
        create,update,delete



=Close Future=
---------------
-Add crypto layer to api @sec

-Crypt Files @sec

-Decentralize the whole architecture @sec

-Find ways to give the single user more ways of democratic participation

-Enable different ways of crypto @sec

-We haven't changed the crypto since the release of the Beta. We plan to overhaul the entire crypto protocol. We're looking for help here! So if you're interested in participating, give us a shout.

-Add an easy way to let everyone develop apps


Update

    Rename 
            elements -> collections
            reader   -> display

    Delete Users where cypher is md5 or sha512



Licensing
 ------------

Please see the file called LICENSE.
..will follow.. plugins need to be checked for their license first.

_____________________
Notes & Ideas


virtual machine
xen and guac


installation hash

A universeInstallation              B transpev server / client who wants to proof
pubKey  privKey   host              pubKey privKey

B sends Request to A
using A pubKey to encrypt
rand
       ||
       ||
       \/
A decrypts rand and adds hash
using host
       ||
       ||
       \/






Group Crypto:

Creation
    User creates Group
        ||
        ||
        \/
    (Salt &) Key will be generated
        ||
        ||
        \/
    Stored encrypted for each user
Invitation
    User A invites User B
        ||
        ||
        \/
    User A decrypts Key, encrypts it with B´s public Key 
        ||
        ||Accepts invitation
        \/
    Decrypts Key and stores it in Key Database

(if one user is fucked, all groups the user has joined are fucked)
enter password when joining the group(forbid same password as the user password, forget password procedure)
joining groups prob
only invite users who are online







List of Views:

GUI
    Applications

    Dock

    Dashboard

Calendar

    Monthview

    Weekview

    Events
        Create      Form
        Update      Form
    Tasks
        Create      Form
        Update      Form

Chat
    Startview
    Dialoge

Filesystem
    Folders and Collections
    showCollection


Elements
    Create          Form
    Update          Form

UFF
    Create          Form

Folders
    Create          Form
    Update          Form

Groups
    Create          Form
    Show            Form

Links
    Create          Form
    Update          Form

to be continued
        