Note: This was an assignment but I do not have the questions here. Sorry, it could be confusing.


Owen Maule Test Driven Development walkthrough

Language used will be PHP.

Step 1.
~~~~~~~

Testing could be applied at the highest level, with the implementation being a black box, however this would not be very thorough testing. Ideally testing should be carried out at all levels. Define the data flow and architecture first, to be tested.

We will be passing manually specified input and testing for expected output. The test routines will, in most cases, not include functionality, as that would mirror the functionality of the implementation and the same bugs or incorrect functionality would likely occur in both. The test routines might have some flexibility to allow for adjustments, for example in the layout, however not the calculation - as layout is not the target of these functionality tests (there may be other tests for that) and we wish to reduce the maintenance of the tests i.e. should the input/output formats be changed, we do not wish that to break the tests of the business logic.
By defining the data flow and architecture, we test against function/method interfaces of the business logic level, rather then the overall input/output of the system - allowing this outer layer to be independently changed. The business logic should be defined in one place, for consistency, and can be wrapped and reused, for example within a interactive model-view-controller system or for batch processing.

For brevity, this exercise will not incorporate any classes. Logical entity associations will be indicated by function naming, i.e. by ending with Item, Items or Receipt. These may or may not be identified as classes within an OO system, depending on the use cases and the rest of the system.

As an implementation detail, I suggest not to use floating point arithmetic for the pricing due to potential for rounding errors. Instead my suggestion here is to use integer pence (or cent) values for all business logic from the point data is parsed into the system until it is rendered to the output. This can also help with internationalisation and other currency formats that have more decimal places e.g. Bitcoin.

It appears this test requires basic tax exemption and import status to be deduced via natural language parsing, which is not recommended for a real system. It may be a better practice to specify additional fields in the input for these.

I have specified data flow and architecture for the system, this could be function declarations, however I have made these stub functions that can be tested against (step 2). These would then be implemented (step 3) and tested again.

See file: dataflow.php

Step 2.
~~~~~~~

Now the data flow is defined and functionality modularised, write the tests against these functional units.

In practice it's often better if the test data used is different at different levels of the same functionality, as otherwise the same tests are carried out multiple times, when the system could be tested for a more varied set of test data during that time, giving better coverage.

See file: tests.php
See file: runTests.php

Step 3.
~~~~~~~

Now implement the functionality and check against the test results until the tests pass.

See file: implementation.php
See file: runTests.php

The include for dataflow.php in runTests.php can be commented out and the include for implementation.php uncommented, to test my implementation.
I expect you will wish to insert some bugs to test the test routines!
