CREATE TABLE In_Collection(
  card_id INT NOT NULL,
  collection_id INT NOT NULL,
  amount INT NOT NULL,
  CONSTRAINT fk_card_in_collection FOREIGN KEY (card_id) REFERENCES Card(card_id),
  CONSTRAINT fk_collection_in_collection FOREIGN KEY (collection_id) REFERENCES Collection(collection_id)
);

CREATE TABLE In_Deck(
  deck_id INT NOT NULL,
  card_id INT NOT NULL,
  CONSTRAINT fk_deck_in_deck FOREIGN KEY (deck_id) REFERENCES Deck(deck_id),
  CONSTRAINT fk_card_in_deck FOREIGN KEY (card_id) REFERENCES Card(card_id)
);

CREATE TABLE Has_Deck(
  deck_id INT NOT NULL,
  collection_id INT NOT NULL,
  CONSTRAINT fk_deck_has_deck FOREIGN KEY (deck_id) REFERENCES Deck(deck_id),
  CONSTRAINT fk_collection_has_deck FOREIGN KEY (collection_id) REFERENCES Collection(collection_id)
);

-- Add users
INSERT INTO User (user_id, first_name, last_name, collection_id, username, password) VALUES( 1,'zach','baklund',1,'user','user');

-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~IN DECK QUERIES ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

-- Add Decks into collections
INSERT INTO Deck(deck_id, deck_name, deck_wins, deck_losses, creation_date) VALUES(1, 'noob slayer', 3, 5, '05/03/2018');

-- Add card to a collection's deck
INSERT INTO In_Deck(deck_id, card_id, card_amount) VALUES(1, 435152, 1); --replace val 1 w/ deck_id, val 2 w/ card_id, val 3 w/ card_amount

-- Add one card to In_Deck card count
UPDATE In_Deck
SET card_amount = 1 + (SELECT card_amount
                       FROM In_Deck
                       WHERE deck_id == 1 AND card_id == 435152) -- replace 2 with new values
WHERE card_id == 435152 AND deck_id == 1; --replace with card_id and deck_id

-- Delete one card from In_Deck card count
UPDATE In_Deck
SET card_amount = (SELECT card_amount
                   FROM In_Deck
                   WHERE deck_id == 1 AND card_id == 435152) - 1 -- replace 2 with new values
WHERE card_id == 435152 AND deck_id == 1; --replace with card_id and deck_id

-- Add one to win count for a Deck
UPDATE In_Deck
SET deck_wins = 1 + (SELECT deck_wins
                     FROM In_Deck
                     WHERE deck_id == 1) -- replace 1 with new values
WHERE deck_id == 1; --replace with deck_id

-- Add one to loss count for a Deck
UPDATE In_Deck
SET deck_losses = 1 + (SELECT deck_losses
                       FROM In_Deck
                       WHERE deck_id == 1) --replace 1 with new values
WHERE deck_id == 1; --replace with deck_id

-- Change Deck Name
UPDATE In_Deck
SET deck_name = 'new name'
WHERE deck_id == 1; --replace with deck_id

-- ~~~~~~~~~~~~~~~~~~~IN COLLECTION QUERIES~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
-- Add Card to user collection
INSERT INTO In_Collection(card_id, collection_id, amount) VALUES(435152, 1, 1); --replace 3 with new values

--Add one to card count
UPDATE In_Collection
SET amount = 1 + (SELECT amount
                  FROM In_Collection 
                  WHERE collection_id == 1 AND card_id == 435289)-- replace 2 with new values
WHERE card_id = 435289 AND collection_id == 1; -- replace with card_id abd collection_id

--Delete one card in collection
UPDATE In_Collection
SET amount = (SELECT amount
              FROM In_Collection
              WHERE collection_id == 1 AND card_id == 435289) - 1 --replace 2 with new values
WHERE card_id == 435289 AND collection_id == 1; --replace with card_id and collection_id

-- ~~~~~~~~~~~~~~~~~~~~~~~~~~ SUMMARY QUERIES ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

-- Updating collection size
UPDATE Collection
SET collection_size = (SELECT sum(amount)
                       FROM In_Collection
                       WHERE collection_id == 1)
WHERE Collection.collection_id == 1;

-- Deleting tuples if amount is 0 or less
DELETE FROM In_Collection
WHERE amount <= 0;

-- Total collection price
SELECT sum(usd_price * amount), sum(usd_foil_price  * amount)
FROM User NATURAL JOIN In_Collection NATURAL JOIN Card
WHERE User.user_id == 1; --replace 1 with user searching

-- Cards in collection not in Decks
SELECT Card.*, In_Collection.amount
FROM User NATURAL JOIN In_Collection LEFT OUTER JOIN In_Deck ON In_Deck.card_id == In_Collection.card_id NATURAL JOIN Card
WHERE deck_id IS NULL AND User.user_id == 1; -- replace 1 with user searching

-- Percent collected unique                                
SELECT user_count.number * 1.0 / card_count.number * 100 AS percent_collected
FROM (SELECT count(*) AS number
      FROM User NATURAL JOIN In_Collection NATURAL JOIN Card
      WHERE User.user_id == 1 ) AS user_count JOIN (SELECT count(*) AS number
                                           FROM Card) AS card_count