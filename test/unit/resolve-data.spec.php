<?php

use League\Plates\{
    Extension\Data\ResolveDataArgs,
    Template
};
use function League\Plates\{
    Extension\Data\idResolveData,
    Util\stack
};

xdescribe('Data Resolvers', function() {
    beforeEach(function() {
        $this->args = new ResolveDataArgs([], new Template(''));
    });

    describe('idResolveData', function() {
        it('returns the data passed in', function() {
            $res = idResolveData()($this->args->withData([1,2,3]));
            expect($res)->equal([1,2,3]);
        });
    });
});
