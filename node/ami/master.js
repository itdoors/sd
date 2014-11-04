var cluster = require('cluster');

//var workers = process.env.WORKERS || require('os').cpus().length;
var workers = 1;

var errorConnectionCount = 0;
var errorConnectionLimit = 5;
var errorConnectionLastTime;
var errorConnectionTimeInterval = 30 * 1000; // In milliseconds

if (cluster.isMaster) {

    console.log('start cluster with %s workers', workers);

    for (var i = 0; i < workers; ++i)
    {
        var worker = cluster.fork().process;
        console.log('worker %s started.', worker.pid);
    }

    cluster.on('exit', function(worker)
    {
        console.log('worker %s died. restart...', worker.process.pid);

        if (canFork())
        {
            cluster.fork();
        }
        else
        {
            process.exit(1);
        }
    });
}
else
{
    require('./index.js');
}

process.on('uncaughtException', function (err) {
    console.error((new Date).toUTCString() + ' uncaughtException:', err.message, ' with code', err.code);
    console.error(err.stack);
    process.exit(1);
});

canFork = function()
{
    errorConnectionCount++;

    var errorConnectionCurrentTime = new Date().getTime();

    /// If count of not valid connection occurred
    if (errorConnectionLastTime && errorConnectionCurrentTime - errorConnectionLastTime < errorConnectionTimeInterval)
    {
        if (errorConnectionCount > errorConnectionLimit)
        {
            process.exit(1);
        }
    }
    else
    {
        errorConnectionCount = 0;
    }

    errorConnectionLastTime = errorConnectionCurrentTime;

    return true;
}