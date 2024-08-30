import { objectValues, assign } from '../../tools/utils/polyfills';
import { createEndpointBuilder } from './endpointBuilder';
import { buildTags } from './tags';
import { INTAKE_SITE_US1, PCI_INTAKE_HOST_US1 } from './intakeSites';
export function computeTransportConfiguration(initConfiguration) {
    var site = initConfiguration.site || INTAKE_SITE_US1;
    var tags = buildTags(initConfiguration);
    var endpointBuilders = computeEndpointBuilders(initConfiguration, tags);
    var intakeUrlPrefixes = computeIntakeUrlPrefixes(endpointBuilders, site);
    var replicaConfiguration = computeReplicaConfiguration(initConfiguration, intakeUrlPrefixes, tags);
    return assign({
        isIntakeUrl: function (url) { return intakeUrlPrefixes.some(function (intakeEndpoint) { return url.indexOf(intakeEndpoint) === 0; }); },
        replica: replicaConfiguration,
        site: site,
    }, endpointBuilders);
}
function computeEndpointBuilders(initConfiguration, tags) {
    return {
        logsEndpointBuilder: createEndpointBuilder(initConfiguration, 'logs', tags),
        rumEndpointBuilder: createEndpointBuilder(initConfiguration, 'rum', tags),
        sessionReplayEndpointBuilder: createEndpointBuilder(initConfiguration, 'replay', tags),
    };
}
function computeReplicaConfiguration(initConfiguration, intakeUrlPrefixes, tags) {
    if (!initConfiguration.replica) {
        return;
    }
    var replicaConfiguration = assign({}, initConfiguration, {
        site: INTAKE_SITE_US1,
        clientToken: initConfiguration.replica.clientToken,
    });
    var replicaEndpointBuilders = {
        logsEndpointBuilder: createEndpointBuilder(replicaConfiguration, 'logs', tags),
        rumEndpointBuilder: createEndpointBuilder(replicaConfiguration, 'rum', tags),
    };
    intakeUrlPrefixes.push.apply(intakeUrlPrefixes, objectValues(replicaEndpointBuilders).map(function (builder) { return builder.urlPrefix; }));
    return assign({ applicationId: initConfiguration.replica.applicationId }, replicaEndpointBuilders);
}
function computeIntakeUrlPrefixes(endpointBuilders, site) {
    var intakeUrlPrefixes = objectValues(endpointBuilders).map(function (builder) { return builder.urlPrefix; });
    if (site === INTAKE_SITE_US1) {
        intakeUrlPrefixes.push("https://".concat(PCI_INTAKE_HOST_US1, "/"));
    }
    return intakeUrlPrefixes;
}
//# sourceMappingURL=transportConfiguration.js.map